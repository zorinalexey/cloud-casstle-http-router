<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\Exceptions\InsecureConnectionException;
use CloudCastle\Http\Router\Route;
use CloudCastle\Http\Router\Router;
use PHPUnit\Framework\TestCase;

class ProtocolSupportTest extends TestCase
{
    private Router $router;

    protected function setUp(): void
    {
        Router::reset();
        $this->router = Router::getInstance();
    }

    public function testProtocolSetting(): void
    {
        $route = new Route(['GET'], '/test', fn() => 'test');
        $route->protocol(['http', 'https']);

        $protocols = $route->getProtocols();
        $this->assertContains('http', $protocols);
        $this->assertContains('https', $protocols);
    }

    public function testHttpsOnlyRoute(): void
    {
        $route = new Route(['GET'], '/secure', fn() => 'secure');
        $route->https();

        $this->assertTrue($route->isHttpsOnly());
        $this->assertTrue($route->requiresHttps());
        $this->assertEquals(['https'], $route->getProtocols());
        $this->assertEquals(443, $route->getPort());
    }

    public function testHttpOrHttpsRoute(): void
    {
        $route = new Route(['GET'], '/flexible', fn() => 'flexible');
        $route->httpOrHttps();

        $this->assertFalse($route->isHttpsOnly());
        $this->assertEquals(['http', 'https'], $route->getProtocols());
    }

    public function testWebSocketProtocol(): void
    {
        $route = new Route(['GET'], '/ws', fn() => 'ws');
        $route->websocket();

        $protocols = $route->getProtocols();
        $this->assertContains('ws', $protocols);
        $this->assertContains('wss', $protocols);
    }

    public function testSecureWebSocketProtocol(): void
    {
        $route = new Route(['GET'], '/wss', fn() => 'wss');
        $route->secureWebsocket();

        $this->assertEquals(['wss'], $route->getProtocols());
    }

    public function testProtocolValidation(): void
    {
        $route = new Route(['GET'], '/https-only', fn() => 'secure');
        $route->protocol(['https']);

        $this->assertTrue($route->isProtocolAllowed('https'));
        $this->assertFalse($route->isProtocolAllowed('http'));
        $this->assertFalse($route->isProtocolAllowed('ws'));
    }

    public function testDispatchWithHttpsRequirement(): void
    {
        $this->router->get('/secure', fn() => 'secure')->https();

        // HTTPS should work
        $route = $this->router->dispatch('/secure', 'GET', null, null, null, 'https');
        $this->assertNotNull($route);

        // HTTP should fail
        $this->expectException(InsecureConnectionException::class);
        $this->router->dispatch('/secure', 'GET', null, null, null, 'http');
    }

    public function testDispatchWithProtocolRestriction(): void
    {
        $this->router->get('/ws', fn() => 'ws')->websocket();

        // WebSocket should work
        $route = $this->router->dispatch('/ws', 'GET', null, null, null, 'ws');
        $this->assertNotNull($route);

        // HTTP should fail
        $this->expectException(InsecureConnectionException::class);
        $this->router->dispatch('/ws', 'GET', null, null, null, 'http');
    }

    public function testGroupWithHttpsRequirement(): void
    {
        $this->router->group(['https' => true], function($router) {
            $router->get('/secure1', fn() => 'secure1');
            $router->get('/secure2', fn() => 'secure2');
        });

        $routes = $this->router->getRoutes();
        
        foreach ($routes as $route) {
            $this->assertTrue($route->isHttpsOnly());
            $this->assertEquals(443, $route->getPort());
        }
    }

    public function testGroupWithProtocol(): void
    {
        $this->router->group(['protocol' => ['http', 'https']], function($router) {
            $router->get('/api/data', fn() => 'data');
        });

        $routes = $this->router->getRoutes();
        $protocols = $routes[0]->getProtocols();
        
        $this->assertContains('http', $protocols);
        $this->assertContains('https', $protocols);
    }

    public function testSecureShortcut(): void
    {
        $route = $this->router->get('/payment', fn() => 'payment')
            ->secure();

        $this->assertTrue($route->isHttpsOnly());
        $this->assertEquals(443, $route->getPort());
    }

    public function testProtocolInheritanceInNestedGroups(): void
    {
        $this->router->group(['protocol' => 'https'], function($router) {
            $router->group(['prefix' => '/api'], function($router) {
                $router->get('/data', fn() => 'data');
            });
        });

        $routes = $this->router->getRoutes();
        $this->assertContains('https', $routes[0]->getProtocols());
    }

    public function testNoProtocolRestriction(): void
    {
        $route = new Route(['GET'], '/open', fn() => 'open');

        // No restriction - all protocols allowed
        $this->assertTrue($route->isProtocolAllowed('http'));
        $this->assertTrue($route->isProtocolAllowed('https'));
        $this->assertTrue($route->isProtocolAllowed('ws'));
        $this->assertTrue($route->isProtocolAllowed('ftp'));
    }
}

