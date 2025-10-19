<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\Route;
use CloudCastle\Http\Router\RouteCollection;
use PHPUnit\Framework\TestCase;

class RouteCollectionTest extends TestCase
{
    private RouteCollection $collection;

    public function testAddRoute(): void
    {
        $route = new Route(['GET'], '/test', fn (): string => 'test');
        $this->collection->add($route);

        $this->assertCount(1, $this->collection);
    }

    public function testExactMatch(): void
    {
        $route1 = new Route(['GET'], '/users', fn (): string => 'users');
        $route2 = new Route(['POST'], '/users', fn (): string => 'create');

        $this->collection->add($route1);
        $this->collection->add($route2);

        $matched = $this->collection->matchExact('/users', 'GET');
        $this->assertSame($route1, $matched);

        $matched = $this->collection->matchExact('/users', 'POST');
        $this->assertSame($route2, $matched);
    }

    public function testExactMatchNotFound(): void
    {
        $route = new Route(['GET'], '/users', fn (): string => 'users');
        $this->collection->add($route);

        $matched = $this->collection->matchExact('/posts', 'GET');
        $this->assertNull($matched);
    }

    public function testGetByName(): void
    {
        $route = new Route(['GET'], '/test', fn (): string => 'test');
        $route->name('test.route');

        $this->collection->add($route);

        $found = $this->collection->getByName('test.route');
        $this->assertSame($route, $found);
    }

    public function testGetByNameNotFound(): void
    {
        $found = $this->collection->getByName('nonexistent');
        $this->assertNull($found);
    }

    public function testIterator(): void
    {
        $route1 = new Route(['GET'], '/one', fn (): string => '1');
        $route2 = new Route(['GET'], '/two', fn (): string => '2');
        $route3 = new Route(['GET'], '/three', fn (): string => '3');

        $this->collection->add($route1);
        $this->collection->add($route2);
        $this->collection->add($route3);

        $count = 0;
        foreach ($this->collection as $route) {
            $this->assertInstanceOf(Route::class, $route);
            $count++;
        }

        $this->assertEquals(3, $count);
    }

    public function testCountable(): void
    {
        $this->assertEquals(0, count($this->collection));

        $this->collection->add(new Route(['GET'], '/one', fn (): string => '1'));
        $this->assertEquals(1, count($this->collection));

        $this->collection->add(new Route(['GET'], '/two', fn (): string => '2'));
        $this->assertEquals(2, count($this->collection));
    }

    public function testArrayAccess(): void
    {
        $route = new Route(['GET'], '/test', fn (): string => 'test');

        $this->collection[] = $route;
        $this->assertTrue(isset($this->collection[0]));
        $this->assertSame($route, $this->collection[0]);
    }

    public function testArrayAccessUnset(): void
    {
        $route = new Route(['GET'], '/test', fn (): string => 'test');
        $this->collection[] = $route;

        $this->assertCount(1, $this->collection);

        unset($this->collection[0]);
        $this->assertFalse(isset($this->collection[0]));
    }

    public function testAll(): void
    {
        $route1 = new Route(['GET'], '/one', fn (): string => '1');
        $route2 = new Route(['GET'], '/two', fn (): string => '2');

        $this->collection->add($route1);
        $this->collection->add($route2);

        $all = $this->collection->all();
        $this->assertIsArray($all);
        $this->assertCount(2, $all);
        $this->assertSame($route1, $all[0]);
        $this->assertSame($route2, $all[1]);
    }

    public function testParameterizedRoutesNotIndexed(): void
    {
        $route = new Route(['GET'], '/users/{id}', fn (): string => 'user');
        $this->collection->add($route);

        // Parameterized routes should not be in exact match index
        $matched = $this->collection->matchExact('/users/123', 'GET');
        $this->assertNull($matched);
    }

    protected function setUp(): void
    {
        $this->collection = new RouteCollection();
    }
}
