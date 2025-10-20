<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\Loader\AttributeLoader;
use CloudCastle\Http\Router\Loader\Route as RouteAttribute;
use CloudCastle\Http\Router\Router;
use PHPUnit\Framework\TestCase;
use RuntimeException;

// Test controller with attributes
class TestAttributeController
{
    #[RouteAttribute('/test', methods : 'GET', name : 'test.index')]
    public function index(): array
    {
        return ['test' => 'index'];
    }

    #[RouteAttribute('/test/{id}', methods : 'GET', name : 'test.show')]
    public function show(int $id): array
    {
        return ['id' => $id];
    }

    #[RouteAttribute('/test', methods : 'POST', name : 'test.store', middleware : 'auth')]
    public function store(): array
    {
        return ['created' => true];
    }

    #[RouteAttribute('/admin', methods : ['GET', 'POST'], name : 'admin.index', middleware : ['auth', 'admin'])]
    public function admin(): array
    {
        return ['admin' => true];
    }

    #[RouteAttribute('/api', methods : 'GET', domain : 'api.example.com', throttle : 60)]
    public function api(): array
    {
        return ['api' => true];
    }

    // Multiple attributes on same method
    #[RouteAttribute('/user/{id}', methods : 'GET')]
    #[RouteAttribute('/profile/{id}', methods : 'GET')]
    public function multipleRoutes(int $id): array
    {
        return ['id' => $id];
    }

    // Public method without attribute
    public function noAttribute(): void
    {
    }
}

class AttributeLoaderTest extends TestCase
{
    private Router $router;

    private AttributeLoader $loader;

    public function testLoadFromController(): void
    {
        $this->loader->loadFromController(TestAttributeController::class);

        $routes = $this->router->getAllRoutes();
        $this->assertGreaterThan(0, count($routes));
    }

    public function testLoadSimpleRoute(): void
    {
        $this->loader->loadFromController(TestAttributeController::class);

        $route = $this->router->getRoute('test.index');
        $this->assertNotNull($route);
        $this->assertEquals('/test', $route->getUri());
        $this->assertEquals(['GET'], $route->getMethods());
    }

    public function testLoadRouteWithParameter(): void
    {
        $this->loader->loadFromController(TestAttributeController::class);

        $route = $this->router->getRoute('test.show');
        $this->assertNotNull($route);
        $this->assertEquals('/test/{id}', $route->getUri());
    }

    public function testLoadRouteWithMiddleware(): void
    {
        $this->loader->loadFromController(TestAttributeController::class);

        $route = $this->router->getRoute('test.store');
        $this->assertNotNull($route);
        $this->assertEquals(['auth'], $route->getMiddleware());
    }

    public function testLoadRouteWithMultipleMiddleware(): void
    {
        $this->loader->loadFromController(TestAttributeController::class);

        $route = $this->router->getRoute('admin.index');
        $this->assertNotNull($route);
        $this->assertEquals(['auth', 'admin'], $route->getMiddleware());
        $this->assertEquals(['GET', 'POST'], $route->getMethods());
    }

    public function testLoadRouteWithDomain(): void
    {
        $this->loader->loadFromController(TestAttributeController::class);

        $routes = array_filter(
            $this->router->getAllRoutes(),
            fn ($r): bool => $r->getDomain() === 'api.example.com'
        );

        $this->assertCount(1, $routes);
    }

    public function testLoadMultipleAttributesOnSameMethod(): void
    {
        $this->loader->loadFromController(TestAttributeController::class);

        $routes = array_filter(
            $this->router->getAllRoutes(),
            fn ($r): bool => in_array($r->getUri(), ['/user/{id}', '/profile/{id}'], true)
        );

        $this->assertCount(2, $routes);
    }

    public function testLoadFromMultipleControllers(): void
    {
        $this->loader->loadFromControllers([
            TestAttributeController::class,
        ]);

        $routes = $this->router->getAllRoutes();
        $this->assertGreaterThan(0, count($routes));
    }

    public function testLoadFromNonExistentController(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Controller class not found');

        /** @phpstan-ignore-next-line */
        $this->loader->loadFromController('NonExistentController');
    }

    public function testLoadFromNonExistentDirectory(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Directory not found');

        $this->loader->loadFromDirectory('/non/existent/directory');
    }

    public function testActionIsArray(): void
    {
        $this->loader->loadFromController(TestAttributeController::class);

        $route = $this->router->getRoute('test.index');
        $this->assertNotNull($route);
        $this->assertIsArray($route->getAction());
        $this->assertEquals([TestAttributeController::class, 'index'], $route->getAction());
    }

    protected function setUp(): void
    {
        $this->router = new Router();
        $this->loader = new AttributeLoader($this->router);
    }
}
