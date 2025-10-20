<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\Loader\YamlLoader;
use CloudCastle\Http\Router\Router;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class YamlLoaderTest extends TestCase
{
    private Router $router;

    private YamlLoader $loader;

    private string $tempFile;

    public function testLoadSimpleRoute(): void
    {
        if (!function_exists('yaml_parse_file')) {
            $this->markTestSkipped('YAML extension not installed');
        }

        $yaml = <<<YAML
            home:
              path: /
              methods: GET
              controller: HomeController::index
            YAML;

        file_put_contents($this->tempFile, $yaml);
        $this->loader->load($this->tempFile);

        $routes = $this->router->getAllRoutes();
        $this->assertCount(1, $routes);
        $this->assertEquals('/', $routes[0]->getUri());
        $this->assertEquals(['GET'], $routes[0]->getMethods());
        $this->assertEquals('home', $routes[0]->getName());
    }

    public function testLoadRouteWithMultipleMethods(): void
    {
        if (!function_exists('yaml_parse_file')) {
            $this->markTestSkipped('YAML extension not installed');
        }

        $yaml = <<<YAML
            users:
              path: /users/{id}
              methods: [GET, POST, PUT]
              controller: UserController::handle
            YAML;

        file_put_contents($this->tempFile, $yaml);
        $this->loader->load($this->tempFile);

        $routes = $this->router->getAllRoutes();
        $this->assertCount(1, $routes);
        $this->assertEquals(['GET', 'POST', 'PUT'], $routes[0]->getMethods());
    }

    public function testLoadRouteWithMiddleware(): void
    {
        if (!function_exists('yaml_parse_file')) {
            $this->markTestSkipped('YAML extension not installed');
        }

        $yaml = <<<YAML
            protected:
              path: /admin
              methods: GET
              controller: AdminController::index
              middleware: auth
            YAML;

        file_put_contents($this->tempFile, $yaml);
        $this->loader->load($this->tempFile);

        $routes = $this->router->getAllRoutes();
        $this->assertCount(1, $routes);
        $this->assertEquals(['auth'], $routes[0]->getMiddleware());
    }

    public function testLoadRouteWithDefaults(): void
    {
        if (!function_exists('yaml_parse_file')) {
            $this->markTestSkipped('YAML extension not installed');
        }

        $yaml = <<<YAML
            page:
              path: /page/{num}
              methods: GET
              controller: PageController::show
              defaults:
                num: 1
            YAML;

        file_put_contents($this->tempFile, $yaml);
        $this->loader->load($this->tempFile);

        $routes = $this->router->getAllRoutes();
        $this->assertCount(1, $routes);
        $this->assertEquals(['num' => 1], $routes[0]->getDefaults());
    }

    public function testLoadRouteWithRequirements(): void
    {
        if (!function_exists('yaml_parse_file')) {
            $this->markTestSkipped('YAML extension not installed');
        }

        $yaml = <<<YAML
            user:
              path: /user/{id}
              methods: GET
              controller: UserController::show
              requirements:
                id: \d+
            YAML;

        file_put_contents($this->tempFile, $yaml);
        $this->loader->load($this->tempFile);

        $routes = $this->router->getAllRoutes();
        $this->assertCount(1, $routes);
    }

    public function testLoadRouteWithDomain(): void
    {
        if (!function_exists('yaml_parse_file')) {
            $this->markTestSkipped('YAML extension not installed');
        }

        $yaml = <<<YAML
            api:
              path: /api
              methods: GET
              controller: ApiController::index
              domain: api.example.com
            YAML;

        file_put_contents($this->tempFile, $yaml);
        $this->loader->load($this->tempFile);

        $routes = $this->router->getAllRoutes();
        $this->assertCount(1, $routes);
        $this->assertEquals('api.example.com', $routes[0]->getDomain());
    }

    public function testLoadRouteWithThrottle(): void
    {
        if (!function_exists('yaml_parse_file')) {
            $this->markTestSkipped('YAML extension not installed');
        }

        $yaml = <<<YAML
            limited:
              path: /limited
              methods: POST
              controller: LimitedController::handle
              throttle:
                max: 10
                decay: 60
            YAML;

        file_put_contents($this->tempFile, $yaml);
        $this->loader->load($this->tempFile);

        $routes = $this->router->getAllRoutes();
        $this->assertCount(1, $routes);
    }

    public function testLoadNonExistentFile(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('YAML file not found');

        $this->loader->load('/non/existent/file.yaml');
    }

    public function testLoadMissingPath(): void
    {
        if (!function_exists('yaml_parse_file')) {
            $this->markTestSkipped('YAML extension not installed');
        }

        $yaml = <<<YAML
            invalid:
              methods: GET
              controller: Controller::index
            YAML;

        file_put_contents($this->tempFile, $yaml);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("missing 'path'");

        $this->loader->load($this->tempFile);
    }

    protected function setUp(): void
    {
        $this->router = new Router();
        $this->loader = new YamlLoader($this->router);
        $this->tempFile = sys_get_temp_dir() . '/test_routes_' . uniqid() . '.yaml';
    }

    protected function tearDown(): void
    {
        if (file_exists($this->tempFile)) {
            unlink($this->tempFile);
        }
    }
}
