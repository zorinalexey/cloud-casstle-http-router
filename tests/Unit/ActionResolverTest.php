<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\ActionResolver;
use CloudCastle\Http\Router\Exceptions\InvalidActionException;
use PHPUnit\Framework\TestCase;

class TestController
{
    public function index(): string
    {
        return 'index';
    }

    public function show(string $id): string
    {
        return "show: $id";
    }
}

class ActionResolverTest extends TestCase
{
    private ActionResolver $resolver;

    protected function setUp(): void
    {
        $this->resolver = new ActionResolver();
    }

    public function testResolveClosure(): void
    {
        $action = function () {
            return 'closure result';
        };

        $result = $this->resolver->resolve($action);
        $this->assertEquals('closure result', $result);
    }

    public function testResolveClosureWithParameters(): void
    {
        $action = function ($id, $name) {
            return "id: $id, name: $name";
        };

        $result = $this->resolver->resolve($action, ['123', 'John']);
        $this->assertEquals('id: 123, name: John', $result);
    }

    public function testResolveArrayWithClass(): void
    {
        $action = [TestController::class, 'index'];
        $result = $this->resolver->resolve($action);

        $this->assertEquals('index', $result);
    }

    public function testResolveArrayWithInstance(): void
    {
        $controller = new TestController();
        $action = [$controller, 'show'];

        $result = $this->resolver->resolve($action, ['123']);
        $this->assertEquals('show: 123', $result);
    }

    public function testResolveStringWithAtSeparator(): void
    {
        $action = TestController::class . '@index';
        $result = $this->resolver->resolve($action);

        $this->assertEquals('index', $result);
    }

    public function testResolveStringWithDoubleColonSeparator(): void
    {
        $action = TestController::class . '::show';
        $result = $this->resolver->resolve($action, ['456']);

        $this->assertEquals('show: 456', $result);
    }

    public function testInvalidActionThrowsException(): void
    {
        $this->expectException(InvalidActionException::class);

        $this->resolver->resolve(123);
    }

    public function testInvalidStringFormatThrowsException(): void
    {
        $this->expectException(InvalidActionException::class);

        $this->resolver->resolve('InvalidFormat');
    }

    public function testNonExistentMethodThrowsException(): void
    {
        $this->expectException(InvalidActionException::class);

        $action = [TestController::class, 'nonExistentMethod'];
        $this->resolver->resolve($action);
    }
}

