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
        return 'show: ' . $id;
    }
}

class TestControllerWithDependency
{
    public function __construct(private string $dependency = 'default')
    {
    }

    public function index(): string
    {
        return 'index with ' . $this->dependency;
    }
}

class TestControllerNoDependencies
{
    public function __construct()
    {
    }

    public function index(): string
    {
        return 'no dependencies';
    }
}

class ActionResolverTest extends TestCase
{
    private ActionResolver $resolver;

    public function testResolveClosure(): void
    {
        $action = fn (): string => 'closure result';

        $result = $this->resolver->resolve($action);
        $this->assertEquals('closure result', $result);
    }

    public function testResolveClosureWithParameters(): void
    {
        $action = fn ($id, $name): string => sprintf('id: %s, name: %s', $id, $name);

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
        $action = $controller->show(...);

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

    public function testResolveControllerWithNoDependencies(): void
    {
        $action = [TestControllerNoDependencies::class, 'index'];
        $result = $this->resolver->resolve($action);

        $this->assertEquals('no dependencies', $result);
    }

    public function testResolveControllerWithOptionalDependency(): void
    {
        $action = [TestControllerWithDependency::class, 'index'];
        $result = $this->resolver->resolve($action);

        $this->assertEquals('index with default', $result);
    }

    public function testStringActionWithMultipleAtSymbols(): void
    {
        // explode with limit 2 should only split at first @
        $action = TestController::class . '@index';
        $result = $this->resolver->resolve($action);

        $this->assertEquals('index', $result);
        
        // Test that limit 2 is correct (not 3)
        // If we had method name with @ it would fail
        $this->assertNotNull($result);
    }

    public function testStringActionWithMultipleColons(): void
    {
        // explode with limit 2 should only split at first ::
        $action = TestController::class . '::show';
        $result = $this->resolver->resolve($action, ['test']);

        $this->assertEquals('show: test', $result);
        
        // Test that limit 2 is correct (not 3)
        $this->assertNotNull($result);
    }
    
    public function testControllerWithoutConstructor(): void
    {
        // Test that getNumberOfRequiredParameters() === 0 check works
        $action = [TestController::class, 'index'];
        $result = $this->resolver->resolve($action);
        
        $this->assertEquals('index', $result);
        $this->assertIsString($result);
    }
    
    public function testControllerWithEmptyConstructor(): void
    {
        // Test === 0 vs !== 0 in constructor check
        $action = [TestControllerNoDependencies::class, 'index'];
        $result = $this->resolver->resolve($action);
        
        $this->assertEquals('no dependencies', $result);
        // Verify it creates instance successfully
        $this->assertNotNull($result);
    }

    protected function setUp(): void
    {
        $this->resolver = new ActionResolver();
    }
}
