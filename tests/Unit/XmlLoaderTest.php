<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\Loader\XmlLoader;
use CloudCastle\Http\Router\Router;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class XmlLoaderTest extends TestCase
{
    private Router $router;

    private XmlLoader $loader;

    private string $tempFile;

    public function testLoadSimpleRoute(): void
    {
        $xml = <<<XML
            <?xml version="1.0" encoding="UTF-8"?>
            <routes>
                <route path="/" name="home" methods="GET" controller="HomeController::index"/>
            </routes>
            XML;

        file_put_contents($this->tempFile, $xml);
        $this->loader->load($this->tempFile);

        $routes = $this->router->getAllRoutes();
        $this->assertCount(1, $routes);
        $this->assertEquals('/', $routes[0]->getUri());
        $this->assertEquals(['GET'], $routes[0]->getMethods());
        $this->assertEquals('home', $routes[0]->getName());
    }

    public function testLoadRouteWithMultipleMethods(): void
    {
        $xml = <<<XML
            <?xml version="1.0" encoding="UTF-8"?>
            <routes>
                <route path="/users" name="users" methods="GET,POST,PUT" controller="UserController::handle"/>
            </routes>
            XML;

        file_put_contents($this->tempFile, $xml);
        $this->loader->load($this->tempFile);

        $routes = $this->router->getAllRoutes();
        $this->assertCount(1, $routes);
        $this->assertEquals(['GET', 'POST', 'PUT'], $routes[0]->getMethods());
    }

    public function testLoadRouteWithMiddleware(): void
    {
        $xml = <<<XML
            <?xml version="1.0" encoding="UTF-8"?>
            <routes>
                <route path="/admin" name="admin" methods="GET" controller="AdminController::index">
                    <middleware>auth,admin</middleware>
                </route>
            </routes>
            XML;

        file_put_contents($this->tempFile, $xml);
        $this->loader->load($this->tempFile);

        $routes = $this->router->getAllRoutes();
        $this->assertCount(1, $routes);
        $this->assertEquals(['auth', 'admin'], $routes[0]->getMiddleware());
    }

    public function testLoadRouteWithDefaults(): void
    {
        $xml = <<<XML
            <?xml version="1.0" encoding="UTF-8"?>
            <routes>
                <route path="/page/{num}" name="page" methods="GET" controller="PageController::show">
                    <defaults>
                        <default param="num" value="1"/>
                    </defaults>
                </route>
            </routes>
            XML;

        file_put_contents($this->tempFile, $xml);
        $this->loader->load($this->tempFile);

        $routes = $this->router->getAllRoutes();
        $this->assertCount(1, $routes);
        $this->assertEquals(['num' => '1'], $routes[0]->getDefaults());
    }

    public function testLoadRouteWithRequirements(): void
    {
        $xml = <<<XML
            <?xml version="1.0" encoding="UTF-8"?>
            <routes>
                <route path="/user/{id}" name="user" methods="GET" controller="UserController::show">
                    <requirements>
                        <requirement param="id" pattern="\d+"/>
                    </requirements>
                </route>
            </routes>
            XML;

        file_put_contents($this->tempFile, $xml);
        $this->loader->load($this->tempFile);

        $routes = $this->router->getAllRoutes();
        $this->assertCount(1, $routes);
    }

    public function testLoadRouteWithDomain(): void
    {
        $xml = <<<XML
            <?xml version="1.0" encoding="UTF-8"?>
            <routes>
                <route path="/api" name="api" methods="GET" controller="ApiController::index" domain="api.example.com"/>
            </routes>
            XML;

        file_put_contents($this->tempFile, $xml);
        $this->loader->load($this->tempFile);

        $routes = $this->router->getAllRoutes();
        $this->assertCount(1, $routes);
        $this->assertEquals('api.example.com', $routes[0]->getDomain());
    }

    public function testLoadMultipleRoutes(): void
    {
        $xml = <<<XML
            <?xml version="1.0" encoding="UTF-8"?>
            <routes>
                <route path="/" name="home" methods="GET" controller="HomeController::index"/>
                <route path="/about" name="about" methods="GET" controller="AboutController::index"/>
                <route path="/contact" name="contact" methods="GET,POST" controller="ContactController::handle"/>
            </routes>
            XML;

        file_put_contents($this->tempFile, $xml);
        $this->loader->load($this->tempFile);

        $routes = $this->router->getAllRoutes();
        $this->assertCount(3, $routes);
    }

    public function testLoadNonExistentFile(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('XML file not found');

        $this->loader->load('/non/existent/file.xml');
    }

    public function testLoadInvalidXml(): void
    {
        file_put_contents($this->tempFile, 'invalid xml content');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Failed to parse XML');

        $this->loader->load($this->tempFile);
    }

    public function testLoadMissingPath(): void
    {
        $xml = <<<XML
            <?xml version="1.0" encoding="UTF-8"?>
            <routes>
                <route name="invalid" methods="GET" controller="Controller::index"/>
            </routes>
            XML;

        file_put_contents($this->tempFile, $xml);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('missing path');

        $this->loader->load($this->tempFile);
    }

    protected function setUp(): void
    {
        $this->router = new Router();
        $this->loader = new XmlLoader($this->router);
        $this->tempFile = sys_get_temp_dir() . '/test_routes_' . uniqid() . '.xml';
    }

    protected function tearDown(): void
    {
        if (file_exists($this->tempFile)) {
            unlink($this->tempFile);
        }
    }
}
