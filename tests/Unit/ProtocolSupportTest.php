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

    public function testProtocolSetting(): void
    {
        $route = new Route(['GET'], '/test', fn (): string => 'test');
        $route->protocol(['http', 'https']);

        $protocols = $route->getProtocols();
        $this->assertContains('http', $protocols);
        $this->assertContains('https', $protocols);
    }

    public function testHttpsOnlyRoute(): void
    {
        $route = new Route(['GET'], '/secure', fn (): string => 'secure');
        $route->https();

        $this->assertTrue($route->isHttpsOnly());
        $this->assertTrue($route->requiresHttps());
        $this->assertEquals(['https'], $route->getProtocols());
        $this->assertEquals(443, $route->getPort());
    }

    public function testHttpOrHttpsRoute(): void
    {
        $route = new Route(['GET'], '/flexible', fn (): string => 'flexible');
        $route->httpOrHttps();

        $this->assertFalse($route->isHttpsOnly());
        $this->assertEquals(['http', 'https'], $route->getProtocols());
    }

    public function testWebSocketProtocol(): void
    {
        $route = new Route(['GET'], '/ws', fn (): string => 'ws');
        $route->websocket();

        $protocols = $route->getProtocols();
        $this->assertContains('ws', $protocols);
        $this->assertContains('wss', $protocols);
    }

    public function testSecureWebSocketProtocol(): void
    {
        $route = new Route(['GET'], '/wss', fn (): string => 'wss');
        $route->secureWebsocket();

        $this->assertEquals(['wss'], $route->getProtocols());
    }

    public function testProtocolValidation(): void
    {
        $route = new Route(['GET'], '/https-only', fn (): string => 'secure');
        $route->protocol(['https']);

        $this->assertTrue($route->isProtocolAllowed('https'));
        $this->assertFalse($route->isProtocolAllowed('http'));
        $this->assertFalse($route->isProtocolAllowed('ws'));
    }

    public function testDispatchWithHttpsRequirement(): void
    {
        $this->router->get('/secure', fn (): string => 'secure')->https();

        // HTTPS should work
        $route = $this->router->dispatch('/secure', 'GET', null, null, null, 'https');
        $this->assertNotNull($route);

        // HTTP should fail
        $this->expectException(InsecureConnectionException::class);
        $this->router->dispatch('/secure', 'GET', null, null, null, 'http');
    }

    public function testDispatchWithProtocolRestriction(): void
    {
        $this->router->get('/ws', fn (): string => 'ws')->websocket();

        // WebSocket should work
        $route = $this->router->dispatch('/ws', 'GET', null, null, null, 'ws');
        $this->assertNotNull($route);

        // HTTP should fail
        $this->expectException(InsecureConnectionException::class);
        $this->router->dispatch('/ws', 'GET', null, null, null, 'http');
    }

    public function testGroupWithHttpsRequirement(): void
    {
        $this->router->group(['https' => true], function ($router): void {
            $router->get('/secure1', fn (): string => 'secure1');
            $router->get('/secure2', fn (): string => 'secure2');
        });

        $routes = $this->router->getRoutes();

        foreach ($routes as $route) {
            $this->assertTrue($route->isHttpsOnly());
            $this->assertEquals(443, $route->getPort());
        }
    }

    public function testGroupWithProtocol(): void
    {
        $this->router->group(['protocol' => ['http', 'https']], function ($router): void {
            $router->get('/api/data', fn (): string => 'data');
        });

        $routes = $this->router->getRoutes();
        $protocols = $routes[0]->getProtocols();

        $this->assertContains('http', $protocols);
        $this->assertContains('https', $protocols);
    }

    public function testSecureShortcut(): void
    {
        $route = $this->router->get('/payment', fn (): string => 'payment')
            ->secure();

        $this->assertTrue($route->isHttpsOnly());
        $this->assertEquals(443, $route->getPort());
    }

    public function testProtocolInheritanceInNestedGroups(): void
    {
        $this->router->group(['protocol' => 'https'], function ($router): void {
            $router->group(['prefix' => '/api'], function ($router): void {
                $router->get('/data', fn (): string => 'data');
            });
        });

        $routes = $this->router->getRoutes();
        $this->assertContains('https', $routes[0]->getProtocols());
    }

    public function testNoProtocolRestriction(): void
    {
        $route = new Route(['GET'], '/open', fn (): string => 'open');

        // No restriction - all protocols allowed
        $this->assertTrue($route->isProtocolAllowed('http'));
        $this->assertTrue($route->isProtocolAllowed('https'));
        $this->assertTrue($route->isProtocolAllowed('ws'));
        $this->assertTrue($route->isProtocolAllowed('ftp'));
    }

    protected function setUp(): void
    {
        Router::reset();
        $this->router = Router::getInstance();
    }
}
