<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\Router;
use PHPUnit\Framework\TestCase;

class AutoNamingTest extends TestCase
{
    private Router $router;

    public function testAutoNamingDisabledByDefault(): void
    {
        $this->assertFalse($this->router->isAutoNamingEnabled());
    }

    public function testEnableAutoNaming(): void
    {
        $this->router->enableAutoNaming();
        $this->assertTrue($this->router->isAutoNamingEnabled());
    }

    public function testDisableAutoNaming(): void
    {
        $this->router->enableAutoNaming();
        $this->router->disableAutoNaming();
        $this->assertFalse($this->router->isAutoNamingEnabled());
    }

    public function testAutoNamingWithSimpleRoute(): void
    {
        $this->router->enableAutoNaming();
        $route = $this->router->get('/users', fn (): string => 'users');

        $this->assertEquals('users.get', $route->getName());
    }

    public function testAutoNamingWithParameterizedRoute(): void
    {
        $this->router->enableAutoNaming();
        $route = $this->router->get('/users/{id}', fn (): string => 'user');

        $this->assertEquals('users.id.get', $route->getName());
    }

    public function testAutoNamingWithNestedRoute(): void
    {
        $this->router->enableAutoNaming();
        $route = $this->router->get('/api/v1/users/{id}', fn (): string => 'user');

        $this->assertEquals('api.v1.users.id.get', $route->getName());
    }

    public function testAutoNamingWithComplexPattern(): void
    {
        $this->router->enableAutoNaming();
        $route = $this->router->get('/users/{id:\d+}/posts/{post}', fn (): string => 'post');

        $this->assertEquals('users.id.posts.post.get', $route->getName());
    }

    public function testAutoNamingWithDifferentMethods(): void
    {
        $this->router->enableAutoNaming();

        $getRoute = $this->router->get('/users', fn (): string => 'list');
        $postRoute = $this->router->post('/users', fn (): string => 'create');
        $putRoute = $this->router->put('/users/{id}', fn (): string => 'update');
        $deleteRoute = $this->router->delete('/users/{id}', fn (): string => 'delete');

        $this->assertEquals('users.get', $getRoute->getName());
        $this->assertEquals('users.post', $postRoute->getName());
        $this->assertEquals('users.id.put', $putRoute->getName());
        $this->assertEquals('users.id.delete', $deleteRoute->getName());
    }

    public function testAutoNamingWithRootRoute(): void
    {
        $this->router->enableAutoNaming();
        $route = $this->router->get('/', fn (): string => 'home');

        $this->assertEquals('root.get', $route->getName());
    }

    public function testAutoNamingDoesNotOverrideExplicitName(): void
    {
        $this->router->enableAutoNaming();
        $route = $this->router->get('/users', fn (): string => 'users')->name('custom.users');

        $this->assertEquals('custom.users', $route->getName());
    }

    public function testAutoNamingWithSpecialCharacters(): void
    {
        $this->router->enableAutoNaming();
        $route = $this->router->get('/api-v1/user_profile', fn (): string => 'profile');

        $this->assertEquals('api.v1.user.profile.get', $route->getName());
    }

    public function testAutoNamingWithMultipleMethods(): void
    {
        $this->router->enableAutoNaming();
        $route = $this->router->match(['GET', 'POST'], '/users', fn (): string => 'users');

        // Should use first method
        $this->assertEquals('users.get', $route->getName());
    }

    public function testAutoNamingDisabledDoesNotSetName(): void
    {
        // Auto-naming is disabled by default
        $route = $this->router->get('/users', fn (): string => 'users');

        $this->assertNull($route->getName());
    }

    public function testAutoNamingWithGroupPrefix(): void
    {
        $this->router->enableAutoNaming();

        $this->router->group(['prefix' => 'api/v1'], function (Router $router): void {
            $router->get('/users', fn (): string => 'users');
        });

        $route = $this->router->getRouteByName('api.v1.users.get');
        $this->assertNotNull($route);
        $this->assertEquals('api/v1/users', $route->getUri());
    }

    public function testAutoNamingPreservesManuallyNamedRoutes(): void
    {
        $this->router->enableAutoNaming();

        $autoRoute = $this->router->get('/auto', fn (): string => 'auto');
        $manualRoute = $this->router->get('/manual', fn (): string => 'manual')->name('my.manual.route');

        $this->assertEquals('auto.get', $autoRoute->getName());
        $this->assertEquals('my.manual.route', $manualRoute->getName());
    }

    public function testAutoNamingFluentInterface(): void
    {
        $result = $this->router->enableAutoNaming();
        $this->assertInstanceOf(Router::class, $result);

        $result = $this->router->disableAutoNaming();
        $this->assertInstanceOf(Router::class, $result);
    }

    public function testAutoNamingWithConsecutiveSlashes(): void
    {
        $this->router->enableAutoNaming();
        $route = $this->router->get('/api//v1///users', fn (): string => 'users');

        // Should normalize multiple slashes to single dot
        $this->assertEquals('api.v1.users.get', $route->getName());
    }

    public function testAutoNamingCaseInsensitiveMethod(): void
    {
        $this->router->enableAutoNaming();
        $route = $this->router->get('/users', fn (): string => 'users');

        // Method should be lowercase
        $name = $route->getName();
        $this->assertNotNull($name);
        $this->assertEquals('users.get', $name);
        $this->assertStringNotContainsString('GET', $name);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->router = new Router();
    }
}
