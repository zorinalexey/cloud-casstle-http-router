<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\Loader\JsonLoader;
use CloudCastle\Http\Router\Router;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @covers \CloudCastle\Http\Router\Loader\JsonLoader
 */
class JsonLoaderTest extends TestCase
{
    private Router $router;

    private JsonLoader $loader;

    private string $tempFile;

    protected function setUp(): void
    {
        $this->router = new Router();
        $this->loader = new JsonLoader($this->router);
        $this->tempFile = '';
    }

    protected function tearDown(): void
    {
        if ($this->tempFile !== '' && file_exists($this->tempFile)) {
            unlink($this->tempFile);
        }
    }

    private function createTempJsonFile(string $content): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'json_route_');
        if ($tempFile === false) {
            throw new \RuntimeException('Failed to create temporary file');
        }
        $this->tempFile = $tempFile;
        file_put_contents($this->tempFile, $content);

        return $this->tempFile;
    }

    public function testLoadSimpleRoute(): void
    {
        $json = json_encode([
            'routes' => [
                [
                    'method' => 'GET',
                    'uri' => '/test',
                    'action' => 'TestController@index',
                    'name' => 'test.index',
                ],
            ],
        ]);

        $file = $this->createTempJsonFile($json !== false ? $json : '{}');
        $this->loader->load($file);

        $route = $this->router->getRoute('test.index');
        $this->assertNotNull($route);
        $this->assertEquals('/test', $route->getUri());
    }

    public function testLoadRouteWithMiddleware(): void
    {
        $json = json_encode([
            'routes' => [
                [
                    'method' => 'GET',
                    'uri' => '/protected',
                    'action' => 'Controller@method',
                    'middleware' => ['auth', 'admin'],
                ],
            ],
        ]);

        $file = $this->createTempJsonFile($json !== false ? $json : '{}');
        $this->loader->load($file);

        $routes = $this->router->getAllRoutes();
        $this->assertCount(1, $routes);
    }

    public function testLoadRouteWithDefaults(): void
    {
        $json = json_encode([
            'routes' => [
                [
                    'method' => 'GET',
                    'uri' => '/users/{id}',
                    'action' => 'UserController@show',
                    'defaults' => ['id' => 1],
                ],
            ],
        ]);

        $file = $this->createTempJsonFile($json !== false ? $json : '{}');
        $this->loader->load($file);

        $routes = $this->router->getAllRoutes();
        $this->assertCount(1, $routes);
        $this->assertEquals(['id' => 1], $routes[0]->getDefaults());
    }

    public function testLoadRouteWithRequirements(): void
    {
        $json = json_encode([
            'routes' => [
                [
                    'method' => 'GET',
                    'uri' => '/users/{id}',
                    'action' => 'UserController@show',
                    'requirements' => ['id' => '\\d+'],
                ],
            ],
        ]);

        $file = $this->createTempJsonFile($json !== false ? $json : '{}');
        $this->loader->load($file);

        $routes = $this->router->getAllRoutes();
        $this->assertCount(1, $routes);
    }

    public function testLoadRouteWithCondition(): void
    {
        $json = json_encode([
            'routes' => [
                [
                    'method' => 'GET',
                    'uri' => '/premium',
                    'action' => 'PremiumController@index',
                    'condition' => 'user.premium == true',
                ],
            ],
        ]);

        $file = $this->createTempJsonFile($json !== false ? $json : '{}');
        $this->loader->load($file);

        $routes = $this->router->getAllRoutes();
        $this->assertCount(1, $routes);
        $this->assertEquals('user.premium == true', $routes[0]->getCondition());
    }

    public function testLoadRouteWithDomain(): void
    {
        $json = json_encode([
            'routes' => [
                [
                    'method' => 'GET',
                    'uri' => '/admin',
                    'action' => 'AdminController@index',
                    'domain' => 'admin.example.com',
                ],
            ],
        ]);

        $file = $this->createTempJsonFile($json !== false ? $json : '{}');
        $this->loader->load($file);

        $routes = $this->router->getAllRoutes();
        $this->assertCount(1, $routes);
    }

    public function testLoadRouteWithPort(): void
    {
        $json = json_encode([
            'routes' => [
                [
                    'method' => 'GET',
                    'uri' => '/api',
                    'action' => 'ApiController@index',
                    'port' => 8080,
                ],
            ],
        ]);

        $file = $this->createTempJsonFile($json !== false ? $json : '{}');
        $this->loader->load($file);

        $routes = $this->router->getAllRoutes();
        $this->assertCount(1, $routes);
    }

    public function testLoadRouteWithProtocol(): void
    {
        $json = json_encode([
            'routes' => [
                [
                    'method' => 'GET',
                    'uri' => '/secure',
                    'action' => 'SecureController@index',
                    'protocol' => 'https',
                ],
            ],
        ]);

        $file = $this->createTempJsonFile($json !== false ? $json : '{}');
        $this->loader->load($file);

        $routes = $this->router->getAllRoutes();
        $this->assertCount(1, $routes);
    }

    public function testLoadRouteWithTags(): void
    {
        $json = json_encode([
            'routes' => [
                [
                    'method' => 'GET',
                    'uri' => '/api/users',
                    'action' => 'ApiController@users',
                    'tags' => ['api', 'public'],
                ],
            ],
        ]);

        $file = $this->createTempJsonFile($json !== false ? $json : '{}');
        $this->loader->load($file);

        $routes = $this->router->getAllRoutes();
        $this->assertCount(1, $routes);
    }

    public function testLoadRouteWithThrottle(): void
    {
        $json = json_encode([
            'routes' => [
                [
                    'method' => 'POST',
                    'uri' => '/api/data',
                    'action' => 'ApiController@data',
                    'throttle' => [
                        'limit' => 100,
                        'per_minutes' => 1,
                    ],
                ],
            ],
        ]);

        $file = $this->createTempJsonFile($json !== false ? $json : '{}');
        $this->loader->load($file);

        $routes = $this->router->getAllRoutes();
        $this->assertCount(1, $routes);
    }

    public function testLoadRouteWithWhitelist(): void
    {
        $json = json_encode([
            'routes' => [
                [
                    'method' => 'GET',
                    'uri' => '/admin',
                    'action' => 'AdminController@index',
                    'whitelist' => ['192.168.1.0/24', '10.0.0.1'],
                ],
            ],
        ]);

        $file = $this->createTempJsonFile($json !== false ? $json : '{}');
        $this->loader->load($file);

        $routes = $this->router->getAllRoutes();
        $this->assertCount(1, $routes);
    }

    public function testLoadRouteWithBlacklist(): void
    {
        $json = json_encode([
            'routes' => [
                [
                    'method' => 'GET',
                    'uri' => '/public',
                    'action' => 'PublicController@index',
                    'blacklist' => ['192.168.99.0/24'],
                ],
            ],
        ]);

        $file = $this->createTempJsonFile($json !== false ? $json : '{}');
        $this->loader->load($file);

        $routes = $this->router->getAllRoutes();
        $this->assertCount(1, $routes);
    }

    public function testLoadRouteGroup(): void
    {
        $json = json_encode([
            'groups' => [
                [
                    'prefix' => '/api',
                    'middleware' => ['api'],
                    'routes' => [
                        [
                            'method' => 'GET',
                            'uri' => '/users',
                            'action' => 'ApiController@users',
                        ],
                        [
                            'method' => 'GET',
                            'uri' => '/posts',
                            'action' => 'ApiController@posts',
                        ],
                    ],
                ],
            ],
        ]);

        $file = $this->createTempJsonFile($json !== false ? $json : '{}');
        $this->loader->load($file);

        $routes = $this->router->getAllRoutes();
        $this->assertCount(2, $routes);
    }

    public function testLoadNestedGroups(): void
    {
        $json = json_encode([
            'groups' => [
                [
                    'prefix' => '/api',
                    'routes' => [],
                    'groups' => [
                        [
                            'prefix' => '/v1',
                            'routes' => [
                                [
                                    'method' => 'GET',
                                    'uri' => '/users',
                                    'action' => 'ApiV1Controller@users',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $file = $this->createTempJsonFile($json !== false ? $json : '{}');
        $this->loader->load($file);

        $routes = $this->router->getAllRoutes();
        $this->assertCount(1, $routes);
    }

    public function testLoadFileNotFound(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('JSON file not found');

        $this->loader->load('/nonexistent/file.json');
    }

    public function testLoadInvalidJson(): void
    {
        $file = $this->createTempJsonFile('{invalid json}');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid JSON');

        $this->loader->load($file);
    }

    public function testLoadNonArrayJson(): void
    {
        $file = $this->createTempJsonFile('"just a string"');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('JSON must contain an object or array');

        $this->loader->load($file);
    }

    public function testLoadMultipleRoutesAndGroups(): void
    {
        $json = json_encode([
            'routes' => [
                [
                    'method' => 'GET',
                    'uri' => '/home',
                    'action' => 'HomeController@index',
                ],
            ],
            'groups' => [
                [
                    'prefix' => '/api',
                    'routes' => [
                        [
                            'method' => 'GET',
                            'uri' => '/status',
                            'action' => 'ApiController@status',
                        ],
                    ],
                ],
            ],
        ]);

        $file = $this->createTempJsonFile($json !== false ? $json : '{}');
        $this->loader->load($file);

        $routes = $this->router->getAllRoutes();
        $this->assertCount(2, $routes);
    }

    public function testLoadRouteWithPathAlias(): void
    {
        $json = json_encode([
            'routes' => [
                [
                    'method' => 'GET',
                    'path' => '/test',  // Using 'path' instead of 'uri'
                    'handler' => 'TestController@index',  // Using 'handler' instead of 'action'
                ],
            ],
        ]);

        $file = $this->createTempJsonFile($json !== false ? $json : '{}');
        $this->loader->load($file);

        $routes = $this->router->getAllRoutes();
        $this->assertCount(1, $routes);
        $this->assertEquals('/test', $routes[0]->getUri());
    }
}
