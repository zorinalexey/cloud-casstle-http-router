<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\Exceptions\IpNotAllowedException;
use CloudCastle\Http\Router\Exceptions\MethodNotAllowedException;
use CloudCastle\Http\Router\Exceptions\RouteNotFoundException;
use CloudCastle\Http\Router\Route;
use CloudCastle\Http\Router\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    private Router $router;

    public function testGetRoute(): void
    {
        $route = $this->router->get('/users', fn (): string => 'users');

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals(['GET'], $route->getMethods());
        $this->assertEquals('/users', $route->getUri());
    }

    public function testPostRoute(): void
    {
        $route = $this->router->post('/users', 'UserController@store');

        $this->assertEquals(['POST'], $route->getMethods());
    }

    public function testPutRoute(): void
    {
        $route = $this->router->put('/users/{id}', 'UserController@update');

        $this->assertEquals(['PUT'], $route->getMethods());
    }

    public function testPatchRoute(): void
    {
        $route = $this->router->patch('/users/{id}', 'UserController@patch');

        $this->assertEquals(['PATCH'], $route->getMethods());
    }

    public function testDeleteRoute(): void
    {
        $route = $this->router->delete('/users/{id}', 'UserController@destroy');

        $this->assertEquals(['DELETE'], $route->getMethods());
    }

    public function testViewRoute(): void
    {
        $route = $this->router->view('/page', 'PageController@show');

        $this->assertEquals(['VIEW'], $route->getMethods());
    }

    public function testCustomRoute(): void
    {
        $route = $this->router->custom('PURGE', '/cache', 'CacheController@purge');

        $this->assertEquals(['PURGE'], $route->getMethods());
    }

    public function testMatchRoute(): void
    {
        $route = $this->router->match(['GET', 'POST'], '/form', 'FormController@handle');

        $this->assertEquals(['GET', 'POST'], $route->getMethods());
    }

    public function testAnyRoute(): void
    {
        $route = $this->router->any('/webhook', 'WebhookController@handle');

        $methods = $route->getMethods();
        $this->assertContains('GET', $methods);
        $this->assertContains('POST', $methods);
        $this->assertContains('PUT', $methods);
        $this->assertContains('DELETE', $methods);
    }

    public function testRouteGroup(): void
    {
        $this->router->group(['prefix' => '/api'], function ($router): void {
            $router->get('/users', function (): void {
            });
        });

        $routes = $this->router->getRoutes();
        $this->assertCount(1, $routes);
        $this->assertEquals('api/users', $routes[0]->getUri());
    }

    public function testNestedRouteGroups(): void
    {
        $this->router->group(['prefix' => '/api'], function ($router): void {
            $router->group(['prefix' => '/v1'], function ($router): void {
                $router->get('/users', function (): void {
                });
            });
        });

        $routes = $this->router->getRoutes();
        $this->assertEquals('api/v1/users', $routes[0]->getUri());
    }

    public function testGroupWithMiddleware(): void
    {
        $this->router->group(['middleware' => 'auth'], function ($router): void {
            $router->get('/profile', function (): void {
            });
        });

        $routes = $this->router->getRoutes();
        $middleware = $routes[0]->getMiddleware();
        $this->assertContains('auth', $middleware);
    }

    public function testGroupWithDomain(): void
    {
        $this->router->group(['domain' => 'api.example.com'], function ($router): void {
            $router->get('/users', function (): void {
            });
        });

        $routes = $this->router->getRoutes();
        $this->assertEquals('api.example.com', $routes[0]->getDomain());
    }

    public function testGroupWithPort(): void
    {
        $this->router->group(['port' => 8080], function ($router): void {
            $router->get('/metrics', function (): void {
            });
        });

        $routes = $this->router->getRoutes();
        $this->assertEquals(8080, $routes[0]->getPort());
    }

    public function testDispatchSuccessful(): void
    {
        $this->router->get('/users/{id}', fn ($id): string => 'User: ' . $id);

        $route = $this->router->dispatch('/users/123', 'GET');

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals(['id' => '123'], $route->getParameters());
    }

    public function testDispatchRouteNotFound(): void
    {
        $this->expectException(RouteNotFoundException::class);

        $this->router->get('/users', function (): void {
        });
        $this->router->dispatch('/posts', 'GET');
    }

    public function testDispatchMethodNotAllowed(): void
    {
        $this->expectException(MethodNotAllowedException::class);

        $this->router->get('/users', function (): void {
        });
        $this->router->dispatch('/users', 'POST');
    }

    public function testDispatchIpNotAllowed(): void
    {
        $this->expectException(IpNotAllowedException::class);

        $this->router->get('/admin', function (): void {
        })
            ->whitelistIp('192.168.1.1');

        $this->router->dispatch('/admin', 'GET', null, '1.2.3.4');
    }

    public function testDispatchWithDomain(): void
    {
        $this->router->get('/dashboard', function (): void {
        })
            ->domain('admin.example.com');

        $route = $this->router->dispatch('/dashboard', 'GET', 'admin.example.com');
        $this->assertInstanceOf(Route::class, $route);
    }

    public function testDispatchWithWrongDomain(): void
    {
        $this->expectException(RouteNotFoundException::class);

        $this->router->get('/dashboard', function (): void {
        })
            ->domain('admin.example.com');

        $this->router->dispatch('/dashboard', 'GET', 'api.example.com');
    }

    public function testDispatchWithPort(): void
    {
        $this->router->get('/metrics', function (): void {
        })
            ->port(8080);

        $route = $this->router->dispatch('/metrics', 'GET', null, null, 8080);
        $this->assertInstanceOf(Route::class, $route);
    }

    public function testDispatchWithWrongPort(): void
    {
        $this->expectException(RouteNotFoundException::class);

        $this->router->get('/metrics', function (): void {
        })
            ->port(8080);

        $this->router->dispatch('/metrics', 'GET', null, null, 80);
    }

    public function testNamedRoute(): void
    {
        $this->router->get('/users', function (): void {
        })
            ->name('users.index');

        $route = $this->router->getRouteByName('users.index');
        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('/users', $route->getUri());
    }

    public function testTaggedRoutes(): void
    {
        $this->router->get('/api/users', function (): void {
        })
            ->tag('api');

        $this->router->get('/api/posts', function (): void {
        })
            ->tag('api');

        $routes = $this->router->getRoutesByTag('api');
        $this->assertCount(2, $routes);
    }

    public function testGetAllRoutes(): void
    {
        $this->router->get('/users', function (): void {
        });
        $this->router->post('/users', function (): void {
        });
        $this->router->get('/posts', function (): void {
        });

        $routes = $this->router->getRoutes();
        $this->assertCount(3, $routes);
    }

    public function testGlobalMiddleware(): void
    {
        $this->router->middleware('global');
        $this->router->get('/users', function (): void {
        });

        $middleware = $this->router->getGlobalMiddleware();
        $this->assertContains('global', $middleware);
    }

    public function testSingletonPattern(): void
    {
        $instance1 = Router::getInstance();
        $instance2 = Router::getInstance();

        $this->assertSame($instance1, $instance2);
    }

    public function testResetSingleton(): void
    {
        $instance1 = Router::getInstance();
        Router::reset();
        $instance2 = Router::getInstance();

        $this->assertNotSame($instance1, $instance2);
    }

    protected function setUp(): void
    {
        $this->router = new Router();
    }
}
