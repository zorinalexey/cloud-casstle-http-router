<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\Router;
use PHPUnit\Framework\TestCase;

class RouteDefaultsTest extends TestCase
{
    private Router $router;

    protected function setUp(): void
    {
        $this->router = new Router();
    }

    public function testSetSingleDefault(): void
    {
        $route = $this->router->get('/page/{num}', fn ($num): string => 'Page ' . $num)
            ->default('num', 1);

        $this->assertEquals(['num' => 1], $route->getDefaults());
    }

    public function testSetMultipleDefaultsIndividually(): void
    {
        $route = $this->router->get('/archive/{year}/{month}', fn ($y, $m): string => 'Archive')
            ->default('year', 2025)
            ->default('month', 1);

        $this->assertEquals(['year' => 2025, 'month' => 1], $route->getDefaults());
    }

    public function testSetMultipleDefaultsAtOnce(): void
    {
        $route = $this->router->get('/archive/{year}/{month}', fn ($y, $m): string => 'Archive')
            ->defaults(['year' => 2025, 'month' => 1]);

        $this->assertEquals(['year' => 2025, 'month' => 1], $route->getDefaults());
    }

    public function testDefaultsAreMerged(): void
    {
        $route = $this->router->get('/test/{a}/{b}/{c}', fn (): string => 'test')
            ->defaults(['a' => 1, 'b' => 2])
            ->defaults(['c' => 3]);

        $this->assertEquals(['a' => 1, 'b' => 2, 'c' => 3], $route->getDefaults());
    }

    public function testDefaultsOverride(): void
    {
        $route = $this->router->get('/test/{num}', fn (): string => 'test')
            ->default('num', 1)
            ->default('num', 2);

        $this->assertEquals(['num' => 2], $route->getDefaults());
    }

    public function testDefaultValueTypes(): void
    {
        $route = $this->router->get('/test/{str}/{int}/{bool}/{null}', fn (): string => 'test')
            ->defaults([
                'str' => 'string',
                'int' => 42,
                'bool' => true,
                'null' => null,
            ]);

        $defaults = $route->getDefaults();
        $this->assertEquals('string', $defaults['str']);
        $this->assertEquals(42, $defaults['int']);
        $this->assertTrue($defaults['bool']);
        $this->assertNull($defaults['null']);
    }

    public function testDefaultsAppliedDuringMatching(): void
    {
        $this->router->get('/page/{num}', fn ($num): string => 'Page ' . $num)
            ->name('page')
            ->default('num', 1);

        // This would normally not match without defaults
        // But we need to test it through the router's matching mechanism
        $routes = $this->router->getAllRoutes();
        $route = $routes[0];

        // Simulate matching
        $route->matches('/page', 'GET');

        $this->assertArrayHasKey('num', $route->getParameters());
        $this->assertEquals(1, $route->getParameters()['num']);
    }

    public function testEmptyDefaults(): void
    {
        $route = $this->router->get('/test', fn (): string => 'test');

        $this->assertEquals([], $route->getDefaults());
    }

    public function testDefaultsFluentInterface(): void
    {
        $route = $this->router->get('/test/{a}', fn (): string => 'test')
            ->default('a', 1)
            ->name('test')
            ->middleware(['auth']);

        $this->assertEquals(['a' => 1], $route->getDefaults());
        $this->assertEquals('test', $route->getName());
        $this->assertEquals(['auth'], $route->getMiddleware());
    }
}
