<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\Router;
use PHPUnit\Framework\TestCase;

class RouterFilteringTest extends TestCase
{
    private Router $router;

    protected function setUp(): void
    {
        $this->router = new Router();
        $this->setupTestRoutes();
    }

    private function setupTestRoutes(): void
    {
        // Named routes
        $this->router->get('/home', fn (): string => 'home')->name('home');
        $this->router->get('/about', fn (): string => 'about')->name('about');

        // Tagged routes
        $this->router->get('/api/users', fn (): string => 'users')->tag('api');
        $this->router->get('/api/posts', fn (): string => 'posts')->tag(['api', 'public']);

        // Domain routes
        $this->router->get('/admin', fn (): string => 'admin')->domain('admin.example.com');
        $this->router->get('/api', fn (): string => 'api')->domain('api.example.com');

        // Port routes
        $this->router->get('/metrics', fn (): string => 'metrics')->port(8080);
        $this->router->get('/health', fn (): string => 'health')->port(8080);

        // IP restricted routes
        $this->router->get('/secure', fn (): string => 'secure')->whitelistIp('192.168.1.1');
        $this->router->get('/blocked', fn (): string => 'blocked')->blacklistIp('1.2.3.4');

        // Middleware routes
        $this->router->get('/protected', fn (): string => 'protected')->middleware('auth');
        $this->router->post('/data', fn (): string => 'data')->middleware(['auth', 'cors']);

        // Throttled routes
        $this->router->get('/limited', fn (): string => 'limited')->throttle(10, 1);

        // Different methods
        $this->router->post('/create', fn (): string => 'create');
        $this->router->put('/update', fn (): string => 'update');
        $this->router->delete('/destroy', fn (): string => 'destroy');
    }

    public function testCurrentRoute(): void
    {
        $route = $this->router->dispatch('/home', 'GET');

        $this->assertNotNull($this->router->current());
        $this->assertEquals($route, $this->router->current());
        $this->assertEquals('home', $this->router->currentRouteName());
    }

    public function testCurrentRouteNamed(): void
    {
        $this->router->dispatch('/home', 'GET');

        $this->assertTrue($this->router->currentRouteNamed('home'));
        $this->assertFalse($this->router->currentRouteNamed('about'));
    }

    public function testPreviousRoute(): void
    {
        // Reset singleton for clean state
        Router::reset();
        $router = Router::getInstance();
        $router->get('/home', fn (): string => 'home')->name('home');
        $router->get('/about', fn (): string => 'about')->name('about');

        // First dispatch
        $router->dispatch('/home', 'GET');
        $this->assertNull($router->previous());

        // Second dispatch
        $router->dispatch('/about', 'GET');
        $this->assertNotNull($router->previous());
        $this->assertEquals('home', $router->previousRouteName());
        $this->assertEquals('/home', $router->previousRouteUri());
    }

    public function testPreviousRouteNamed(): void
    {
        // Reset singleton for clean state
        Router::reset();
        $router = Router::getInstance();
        $router->get('/home', fn (): string => 'home')->name('home');
        $router->get('/about', fn (): string => 'about')->name('about');

        $router->dispatch('/home', 'GET');
        $router->dispatch('/about', 'GET');

        $this->assertTrue($router->previousRouteNamed('home'));
        $this->assertFalse($router->previousRouteNamed('about'));
    }

    public function testRouteHistory(): void
    {
        // Reset singleton for clean state
        Router::reset();
        $router = Router::getInstance();
        $router->get('/home', fn (): string => 'home')->name('home');
        $router->get('/about', fn (): string => 'about')->name('about');
        $router->get('/api/users', fn (): string => 'users')->tag('api');

        // Navigate through routes
        $router->dispatch('/home', 'GET');
        $this->assertEquals('home', $router->currentRouteName());
        $this->assertNull($router->previousRouteName());

        $router->dispatch('/about', 'GET');
        $this->assertEquals('about', $router->currentRouteName());
        $this->assertEquals('home', $router->previousRouteName());

        $router->dispatch('/api/users', 'GET');
        $this->assertContains('api', $router->current()?->getTags() ?? []);
        $this->assertEquals('about', $router->previousRouteName());
    }

    public function testGetRoutesByMethod(): void
    {
        $getRoutes = $this->router->getRoutesByMethod('GET');
        $postRoutes = $this->router->getRoutesByMethod('POST');

        $this->assertGreaterThan(0, count($getRoutes));
        $this->assertGreaterThan(0, count($postRoutes));
    }

    public function testGetRoutesByDomain(): void
    {
        $routes = $this->router->getRoutesByDomain('admin.example.com');

        $this->assertCount(1, $routes);
    }

    public function testGetRoutesByPort(): void
    {
        $routes = $this->router->getRoutesByPort(8080);

        $this->assertCount(2, $routes);
    }

    public function testGetRoutesByWhitelistedIp(): void
    {
        $routes = $this->router->getRoutesByWhitelistedIp('192.168.1.1');

        $this->assertGreaterThan(0, count($routes));
    }

    public function testGetRoutesByBlacklistedIp(): void
    {
        $routes = $this->router->getRoutesByBlacklistedIp('1.2.3.4');

        $this->assertGreaterThan(0, count($routes));
    }

    public function testGetRoutesByMiddleware(): void
    {
        $routes = $this->router->getRoutesByMiddleware('auth');

        $this->assertGreaterThan(0, count($routes));
    }

    public function testGetThrottledRoutes(): void
    {
        $routes = $this->router->getThrottledRoutes();

        $this->assertGreaterThan(0, count($routes));
    }

    public function testGetRoutesByPrefix(): void
    {
        $routes = $this->router->getRoutesByPrefix('/api');

        $this->assertGreaterThan(0, count($routes));
    }

    public function testGetRoutesWithIpRestrictions(): void
    {
        $routes = $this->router->getRoutesWithIpRestrictions();

        $this->assertGreaterThan(0, count($routes));
    }

    public function testGetRoutesWithDomain(): void
    {
        $routes = $this->router->getRoutesWithDomain();

        $this->assertCount(2, $routes);
    }

    public function testGetRoutesWithPort(): void
    {
        $routes = $this->router->getRoutesWithPort();

        $this->assertCount(2, $routes);
    }

    public function testGetRouteStats(): void
    {
        $stats = $this->router->getRouteStats();

        $this->assertArrayHasKey('total', $stats);
        $this->assertArrayHasKey('named', $stats);
        $this->assertArrayHasKey('tagged', $stats);
        $this->assertArrayHasKey('with_middleware', $stats);
        $this->assertArrayHasKey('throttled', $stats);
        $this->assertArrayHasKey('by_method', $stats);

        $this->assertGreaterThan(0, $stats['total']);
    }

    public function testSearchRoutes(): void
    {
        $routes = $this->router->searchRoutes([
            'tag' => 'api',
            'method' => 'GET',
        ]);

        $this->assertGreaterThan(0, count($routes));

        foreach ($routes as $route) {
            $this->assertContains('api', $route->getTags());
            $this->assertContains('GET', $route->getMethods());
        }
    }

    public function testHasRoute(): void
    {
        $this->assertTrue($this->router->hasRoute('home'));
        $this->assertFalse($this->router->hasRoute('nonexistent'));
    }

    public function testHasTag(): void
    {
        $this->assertTrue($this->router->hasTag('api'));
        $this->assertFalse($this->router->hasTag('nonexistent'));
    }

    public function testGetAllTags(): void
    {
        $tags = $this->router->getAllTags();

        $this->assertContains('api', $tags);
        $this->assertContains('public', $tags);
    }

    public function testGetAllDomains(): void
    {
        $domains = $this->router->getAllDomains();

        $this->assertContains('admin.example.com', $domains);
        $this->assertContains('api.example.com', $domains);
    }

    public function testGetAllPorts(): void
    {
        $ports = $this->router->getAllPorts();

        $this->assertContains(8080, $ports);
    }

    public function testGetRoutesByUriPattern(): void
    {
        $routes = $this->router->getRoutesByUriPattern('api');

        $this->assertGreaterThan(0, count($routes));
    }

    public function testComplexSearch(): void
    {
        $routes = $this->router->searchRoutes([
            'prefix' => '/api',
            'tag' => 'public',
            'has_domain' => false,
        ]);

        $this->assertGreaterThan(0, count($routes));
    }
}
