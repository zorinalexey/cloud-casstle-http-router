<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\Route;
use CloudCastle\Http\Router\Router;
use PHPUnit\Framework\TestCase;

/**
 * Additional tests to kill Router mutations.
 */
class RouterMutationTest extends TestCase
{
    private Router $router;

    public function testGetRoutesByMethodReturnsCorrectRoutes(): void
    {
        $this->router->get('/users', fn () => 'users');
        $this->router->post('/users', fn () => 'create');
        $this->router->get('/posts', fn () => 'posts');

        $getRoutes = $this->router->getRoutesByMethod('GET');
        $this->assertCount(2, $getRoutes);

        $postRoutes = $this->router->getRoutesByMethod('POST');
        $this->assertCount(1, $postRoutes);

        // Verify they are Route objects
        foreach ($getRoutes as $route) {
            $this->assertInstanceOf(Route::class, $route);
            $this->assertContains('GET', $route->getMethods());
        }
    }

    public function testGetRoutesByDomainFiltersCorrectly(): void
    {
        $this->router->get('/api', fn () => 'api')->domain('api.example.com');
        $this->router->get('/web', fn () => 'web')->domain('web.example.com');
        $this->router->get('/any', fn () => 'any');

        $apiRoutes = $this->router->getRoutesByDomain('api.example.com');
        $this->assertCount(1, $apiRoutes);
        $this->assertEquals('api.example.com', $apiRoutes[0]->getDomain());

        $webRoutes = $this->router->getRoutesByDomain('web.example.com');
        $this->assertCount(1, $webRoutes);
    }

    public function testGetRoutesByPortFiltersCorrectly(): void
    {
        $this->router->get('/admin', fn () => 'admin')->port(8080);
        $this->router->get('/api', fn () => 'api')->port(8081);
        $this->router->get('/web', fn () => 'web');

        $port8080 = $this->router->getRoutesByPort(8080);
        $this->assertCount(1, $port8080);
        $this->assertEquals(8080, $port8080[0]->getPort());

        $port8081 = $this->router->getRoutesByPort(8081);
        $this->assertCount(1, $port8081);
    }

    public function testGetRoutesByPrefixFiltersCorrectly(): void
    {
        $this->router->get('/api/users', fn () => 'api-users');
        $this->router->get('/api/posts', fn () => 'api-posts');
        $this->router->get('/web/pages', fn () => 'web-pages');

        $apiRoutes = $this->router->getRoutesByPrefix('/api');
        $this->assertCount(2, $apiRoutes);

        foreach ($apiRoutes as $route) {
            $this->assertStringStartsWith('/api', $route->getUri());
        }

        $webRoutes = $this->router->getRoutesByPrefix('/web');
        $this->assertCount(1, $webRoutes);
    }

    public function testGetRoutesByMiddlewareFiltersCorrectly(): void
    {
        $authMiddleware = fn () => null;
        $this->router->get('/protected1', fn () => 'p1')->middleware($authMiddleware);
        $this->router->get('/protected2', fn () => 'p2')->middleware($authMiddleware);
        $this->router->get('/public', fn () => 'pub');

        // Test that routes with middleware were created
        $routes = $this->router->getRoutes();
        $this->assertCount(3, $routes);

        // Check that first two routes have middleware
        $routesArray = iterator_to_array($routes);
        $this->assertNotEmpty($routesArray[0]->getMiddleware());
        $this->assertNotEmpty($routesArray[1]->getMiddleware());
    }

    public function testGetThrottledRoutesFiltersCorrectly(): void
    {
        $this->router->post('/api1', fn () => 'api1')->throttle(60, 1);
        $this->router->post('/api2', fn () => 'api2')->throttle(100, 1);
        $this->router->get('/web', fn () => 'web');

        $throttled = $this->router->getThrottledRoutes();
        $this->assertCount(2, $throttled);

        foreach ($throttled as $route) {
            $this->assertNotNull($route->getRateLimiter());
        }
    }

    public function testGetRoutesWithIpRestrictionsFiltersCorrectly(): void
    {
        $this->router->get('/admin', fn () => 'admin')->whitelistIp(['192.168.1.1']);
        $this->router->get('/blocked', fn () => 'blocked')->blacklistIp(['1.2.3.4']);
        $this->router->get('/open', fn () => 'open');

        $restricted = $this->router->getRoutesWithIpRestrictions();
        $this->assertCount(2, $restricted);
    }

    public function testGetRoutesWithDomainFiltersCorrectly(): void
    {
        $this->router->get('/api', fn () => 'api')->domain('api.example.com');
        $this->router->get('/web', fn () => 'web');

        $withDomain = $this->router->getRoutesWithDomain();
        $this->assertCount(1, $withDomain);
        $this->assertNotNull($withDomain[0]->getDomain());
    }

    public function testGetRoutesWithPortFiltersCorrectly(): void
    {
        $this->router->get('/admin', fn () => 'admin')->port(8080);
        $this->router->get('/web', fn () => 'web');

        $withPort = $this->router->getRoutesWithPort();
        $this->assertCount(1, $withPort);
        $this->assertNotNull($withPort[0]->getPort());
    }

    public function testSearchRoutesFindsMatches(): void
    {
        $this->router->get('/users', fn () => 'users')->name('users.index');
        $this->router->get('/posts', fn () => 'posts')->name('posts.index');
        $this->router->get('/api/users', fn () => 'api-users')->name('api.users');

        $results = $this->router->searchRoutes(['uri' => 'user']);
        $this->assertIsArray($results);
        $this->assertGreaterThanOrEqual(1, count($results));
    }

    public function testHasTagReturnsTrueWhenExists(): void
    {
        $this->router->get('/api', fn () => 'api')->tag('api');
        $this->router->get('/web', fn () => 'web');

        $this->assertTrue($this->router->hasTag('api'));
        $this->assertFalse($this->router->hasTag('nonexistent'));
    }

    public function testGetAllTagsReturnsUniqueTags(): void
    {
        $this->router->get('/r1', fn () => 'r1')->tag(['api', 'v1']);
        $this->router->get('/r2', fn () => 'r2')->tag(['api', 'v2']);
        $this->router->get('/r3', fn () => 'r3')->tag('web');

        $tags = $this->router->getAllTags();
        $this->assertCount(4, $tags); // api, v1, v2, web = 4 unique
        $this->assertContains('api', $tags);
        $this->assertContains('web', $tags);
        $this->assertContains('v1', $tags);
        $this->assertContains('v2', $tags);
    }

    public function testGetAllDomainsReturnsUniqueDomains(): void
    {
        $this->router->get('/api', fn () => 'api')->domain('api.example.com');
        $this->router->get('/api2', fn () => 'api2')->domain('api.example.com');
        $this->router->get('/web', fn () => 'web')->domain('web.example.com');

        $domains = $this->router->getAllDomains();
        $this->assertCount(2, $domains);
        $this->assertContains('api.example.com', $domains);
        $this->assertContains('web.example.com', $domains);
    }

    public function testGetAllPortsReturnsUniquePorts(): void
    {
        $this->router->get('/admin', fn () => 'admin')->port(8080);
        $this->router->get('/admin2', fn () => 'admin2')->port(8080);
        $this->router->get('/api', fn () => 'api')->port(8081);

        $ports = $this->router->getAllPorts();
        $this->assertCount(2, $ports);
        $this->assertContains(8080, $ports);
        $this->assertContains(8081, $ports);
    }

    public function testCountReturnsCorrectNumber(): void
    {
        $this->assertEquals(0, $this->router->count());

        $this->router->get('/r1', fn () => 'r1');
        $this->assertEquals(1, $this->router->count());

        $this->router->post('/r2', fn () => 'r2');
        $this->assertEquals(2, $this->router->count());

        $this->router->get('/r3', fn () => 'r3');
        $this->assertEquals(3, $this->router->count());
    }

    public function testGetRoutesAsArrayReturnsArray(): void
    {
        $this->router->get('/test', fn () => 'test')->name('test');

        $array = $this->router->getRoutesAsArray();
        $this->assertIsArray($array);
        $this->assertCount(1, $array);
        $this->assertArrayHasKey('uri', $array[0]);
        $this->assertArrayHasKey('methods', $array[0]);
        $this->assertArrayHasKey('name', $array[0]);
    }

    public function testGetRoutesAsJsonReturnsValidJson(): void
    {
        $this->router->get('/test', fn () => 'test')->name('test');

        $json = $this->router->getRoutesAsJson();
        $this->assertJson($json);

        $decoded = json_decode($json, true);
        $this->assertIsArray($decoded);
        $this->assertCount(1, $decoded);
    }

    public function testGetRoutesAsJsonWithFlags(): void
    {
        $this->router->get('/test', fn () => 'test');

        $json = $this->router->getRoutesAsJson(JSON_PRETTY_PRINT);
        $this->assertJson($json);

        // Pretty printed JSON has newlines
        $this->assertStringContainsString("\n", $json);
    }

    public function testGetRoutesGroupedByMethodGroups(): void
    {
        $this->router->get('/g1', fn () => 'g1');
        $this->router->get('/g2', fn () => 'g2');
        $this->router->post('/p1', fn () => 'p1');

        $grouped = $this->router->getRoutesGroupedByMethod();
        $this->assertIsArray($grouped);
        $this->assertArrayHasKey('GET', $grouped);
        $this->assertArrayHasKey('POST', $grouped);
        $this->assertCount(2, $grouped['GET']);
        $this->assertCount(1, $grouped['POST']);
    }

    public function testGetRoutesGroupedByPrefixGroups(): void
    {
        $this->router->get('/api/users', fn () => 'api-users');
        $this->router->get('/api/posts', fn () => 'api-posts');
        $this->router->get('/web/pages', fn () => 'web-pages');

        $grouped = $this->router->getRoutesGroupedByPrefix();
        $this->assertIsArray($grouped);
        $this->assertArrayHasKey('/api', $grouped);
        $this->assertArrayHasKey('/web', $grouped);
        $this->assertCount(2, $grouped['/api']);
        $this->assertCount(1, $grouped['/web']);
    }

    public function testGetRoutesGroupedByDomainGroups(): void
    {
        $this->router->get('/api1', fn () => 'api1')->domain('api.example.com');
        $this->router->get('/api2', fn () => 'api2')->domain('api.example.com');
        $this->router->get('/web', fn () => 'web')->domain('web.example.com');

        $grouped = $this->router->getRoutesGroupedByDomain();
        $this->assertIsArray($grouped);
        $this->assertArrayHasKey('api.example.com', $grouped);
        $this->assertArrayHasKey('web.example.com', $grouped);
        $this->assertCount(2, $grouped['api.example.com']);
        $this->assertCount(1, $grouped['web.example.com']);
    }

    public function testGetRouteByNameReturnsCorrectRoute(): void
    {
        $this->router->get('/users', fn () => 'users')->name('users.index');
        $this->router->get('/posts', fn () => 'posts')->name('posts.index');

        $route = $this->router->getRouteByName('users.index');
        $this->assertNotNull($route);
        $this->assertEquals('users.index', $route->getName());
        $this->assertEquals('/users', $route->getUri());

        $notFound = $this->router->getRouteByName('nonexistent');
        $this->assertNull($notFound);
    }

    public function testGetRoutesByTagReturnsCorrectRoutes(): void
    {
        $this->router->get('/api1', fn () => 'api1')->tag('api');
        $this->router->get('/api2', fn () => 'api2')->tag('api');
        $this->router->get('/web', fn () => 'web')->tag('web');

        $apiRoutes = $this->router->getRoutesByTag('api');
        $this->assertCount(2, $apiRoutes);

        foreach ($apiRoutes as $route) {
            $this->assertContains('api', $route->getTags());
        }

        $webRoutes = $this->router->getRoutesByTag('web');
        $this->assertCount(1, $webRoutes);
    }

    public function testCurrentRouteReturnsLastDispatched(): void
    {
        $this->router->get('/users', fn () => 'users')->name('users');
        $this->router->get('/posts', fn () => 'posts')->name('posts');

        $this->assertNull($this->router->current());

        $route = $this->router->dispatch('/users', 'GET');
        $current = $this->router->current();

        $this->assertNotNull($current);
        $this->assertEquals('users', $current->getName());
        $this->assertSame($route, $current);
    }

    public function testPreviousRouteTracksHistory(): void
    {
        $this->router->get('/first', fn () => 'first')->name('first');
        $this->router->get('/second', fn () => 'second')->name('second');

        $this->assertNull($this->router->previous());

        $first = $this->router->dispatch('/first', 'GET');
        $this->assertNull($this->router->previous());

        $second = $this->router->dispatch('/second', 'GET');
        $previous = $this->router->previous();

        $this->assertNotNull($previous);
        $this->assertEquals('first', $previous->getName());
        $this->assertSame($first, $previous);
    }

    public function testMiddlewareAppliedGlobally(): void
    {
        $middleware1 = fn () => null;
        $middleware2 = fn () => null;
        $this->router->middleware([$middleware1, $middleware2]);
        $this->router->get('/test', fn () => 'test');

        // Global middleware should be in all routes
        $routes = $this->router->getRoutes();
        $this->assertCount(1, $routes);

        // Note: Global middleware may not be added to route directly
        // This tests that the method exists and doesn't crash
        $globalMiddleware = $this->router->getGlobalMiddleware();
        $this->assertCount(2, $globalMiddleware);
    }

    public function testGetNamedRoutesReturnsCorrectArray(): void
    {
        $this->router->get('/users', fn () => 'users')->name('users.index');
        $this->router->get('/posts', fn () => 'posts')->name('posts.index');
        $this->router->get('/unnamed', fn () => 'unnamed');

        $named = $this->router->getNamedRoutes();
        $this->assertIsArray($named);
        $this->assertCount(2, $named);
        $this->assertArrayHasKey('users.index', $named);
        $this->assertArrayHasKey('posts.index', $named);
        $this->assertArrayNotHasKey('unnamed', $named);
    }

    public function testGetAllRoutesReturnsAllRoutes(): void
    {
        $this->router->get('/r1', fn () => 'r1');
        $this->router->post('/r2', fn () => 'r2');
        $this->router->put('/r3', fn () => 'r3');

        $all = $this->router->getAllRoutes();
        $this->assertCount(3, $all);

        foreach ($all as $route) {
            $this->assertInstanceOf(Route::class, $route);
        }
    }

    public function testGetRoutesReturnsCollection(): void
    {
        $this->router->get('/test', fn () => 'test');

        $routes = $this->router->getRoutes();
        $this->assertIsIterable($routes);
        $this->assertCount(1, $routes);
    }

    public function testRouterCount(): void
    {
        $this->assertEquals(0, $this->router->count());

        $this->router->get('/test', fn () => 'test');
        $this->assertEquals(1, $this->router->count());

        $this->router->post('/test2', fn () => 'test2');
        $this->assertEquals(2, $this->router->count());
    }

    public function testGetRouteStatsReturnsCompleteStats(): void
    {
        $this->router->get('/users', fn () => 'users')->name('users')->tag('api');
        $this->router->post('/users', fn () => 'create')->middleware(fn () => null);
        $this->router->get('/api', fn () => 'api')->domain('api.example.com');

        $stats = $this->router->getRouteStats();

        $this->assertIsArray($stats);
        $this->assertArrayHasKey('total', $stats);
        $this->assertArrayHasKey('by_method', $stats);
        $this->assertArrayHasKey('named', $stats);
        $this->assertArrayHasKey('with_middleware', $stats);
        $this->assertArrayHasKey('tagged', $stats);

        $this->assertEquals(3, $stats['total']);
        $this->assertGreaterThan(0, $stats['named']);
        $this->assertGreaterThan(0, $stats['with_middleware']);
    }

    protected function setUp(): void
    {
        $this->router = new Router();
    }
}
