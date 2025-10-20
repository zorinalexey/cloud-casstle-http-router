<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\RouteDumper;
use CloudCastle\Http\Router\Router;
use PHPUnit\Framework\TestCase;

class RouteDumperTest extends TestCase
{
    private Router $router;

    private RouteDumper $dumper;

    public function testDumpReturnsArray(): void
    {
        $dump = $this->dumper->dump();

        $this->assertIsArray($dump);
        $this->assertCount(4, $dump);
    }

    public function testDumpContainsRouteData(): void
    {
        $dump = $this->dumper->dump();
        $homeRoute = $dump[0];

        $this->assertArrayHasKey('methods', $homeRoute);
        $this->assertArrayHasKey('uri', $homeRoute);
        $this->assertArrayHasKey('name', $homeRoute);
        $this->assertArrayHasKey('action', $homeRoute);
        $this->assertArrayHasKey('middleware', $homeRoute);
    }

    public function testDumpJsonReturnsValidJson(): void
    {
        $json = $this->dumper->dumpJson();

        $this->assertJson($json);
        $decoded = json_decode($json, true);
        $this->assertIsArray($decoded);
        $this->assertCount(4, $decoded);
    }

    public function testDumpTableReturnsString(): void
    {
        $table = $this->dumper->dumpTable();

        $this->assertIsString($table);
        $this->assertStringContainsString('METHOD', $table);
        $this->assertStringContainsString('URI', $table);
        $this->assertStringContainsString('NAME', $table);
        $this->assertStringContainsString('ACTION', $table);
    }

    public function testDumpTableContainsRoutes(): void
    {
        $table = $this->dumper->dumpTable();

        $this->assertStringContainsString('/', $table);
        $this->assertStringContainsString('home', $table);
        $this->assertStringContainsString('users.index', $table);
    }

    public function testDumpIncludesMiddleware(): void
    {
        $dump = $this->dumper->dump();
        $userShowRoute = array_values(array_filter($dump, fn ($r): bool => $r['name'] === 'users.show'))[0];

        $this->assertNotEmpty($userShowRoute['middleware']);
        $this->assertTrue(in_array('auth', $userShowRoute['middleware'], true));
    }

    public function testDumpIncludesDefaults(): void
    {
        $dump = $this->dumper->dump();
        $userShowRoute = array_values(array_filter($dump, fn ($r): bool => $r['name'] === 'users.show'))[0];

        $this->assertArrayHasKey('defaults', $userShowRoute);
        $this->assertEquals(['id' => 1], $userShowRoute['defaults']);
    }

    public function testDumpFormatsClosureAction(): void
    {
        $dump = $this->dumper->dump();
        $homeRoute = $dump[0];

        $this->assertEquals('Closure', $homeRoute['action']);
    }

    public function testDumpFormatsArrayAction(): void
    {
        $this->router->get('/test', ['TestController', 'index'])->name('test');
        $dump = $this->dumper->dump();
        $testRoute = array_values(array_filter($dump, fn ($r): bool => $r['name'] === 'test'))[0];

        $action = is_string($testRoute['action']) ? $testRoute['action'] : '';
        $this->assertStringContainsString('TestController@index', $action);
    }

    public function testDumpFormatsStringAction(): void
    {
        $this->router->get('/string', 'StringController@action')->name('string');
        $dump = $this->dumper->dump();
        $stringRoute = array_values(array_filter($dump, fn ($r): bool => $r['name'] === 'string'))[0];

        $this->assertEquals('StringController@action', $stringRoute['action']);
    }

    public function testDumpEmptyRouter(): void
    {
        $emptyRouter = new Router();
        $emptyDumper = new RouteDumper($emptyRouter);

        $dump = $emptyDumper->dump();
        $this->assertIsArray($dump);
        $this->assertEmpty($dump);
    }

    public function testDumpJsonPrettyPrint(): void
    {
        $json = $this->dumper->dumpJson();

        // Check if JSON is pretty printed (contains newlines and indentation)
        $this->assertStringContainsString("\n", $json);
        $this->assertStringContainsString('    ', $json);
    }

    protected function setUp(): void
    {
        $this->router = new Router();
        $this->dumper = new RouteDumper($this->router);

        // Add test routes
        $this->router->get('/', fn (): string => 'home')->name('home');
        $this->router->get('/users', fn (): string => 'users')->name('users.index');
        $this->router->post('/users', fn (): string => 'create')->name('users.store');
        $this->router->get('/users/{id}', fn ($id): string => 'user ' . $id)->name('users.show')
            ->middleware(['auth'])
            ->default('id', 1);
    }
}
