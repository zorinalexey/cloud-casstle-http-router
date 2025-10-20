<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\Exceptions\RouteNotFoundException;
use CloudCastle\Http\Router\Router;
use CloudCastle\Http\Router\UrlMatcher;
use PHPUnit\Framework\TestCase;

class UrlMatcherTest extends TestCase
{
    private Router $router;

    private UrlMatcher $matcher;

    public function testMatchSimpleRoute(): void
    {
        $result = $this->matcher->match('/', 'GET');

        $this->assertEquals('home', $result['route']->getName());
        $this->assertEmpty($result['parameters']);
    }

    public function testMatchRouteWithParameter(): void
    {
        $result = $this->matcher->match('/users/123', 'GET');

        $this->assertEquals('users.show', $result['route']->getName());
        $this->assertEquals(['id' => '123'], $result['parameters']);
    }

    public function testMatchRouteWithMultipleParameters(): void
    {
        $result = $this->matcher->match('/posts/2025/10/my-post', 'GET');

        $this->assertEquals('posts.show', $result['route']->getName());
        $this->assertEquals([
            'year' => '2025',
            'month' => '10',
            'slug' => 'my-post',
        ], $result['parameters']);
    }

    public function testMatchByMethod(): void
    {
        $getResult = $this->matcher->match('/users', 'GET');
        $this->assertEquals('users.index', $getResult['route']->getName());

        $postResult = $this->matcher->match('/users', 'POST');
        $this->assertEquals('users.store', $postResult['route']->getName());
    }

    public function testMatchNotFoundUrl(): void
    {
        $this->expectException(RouteNotFoundException::class);
        $this->expectExceptionMessage('No route matches URL: /not-found');

        $this->matcher->match('/not-found', 'GET');
    }

    public function testMatchWrongMethod(): void
    {
        $this->expectException(RouteNotFoundException::class);

        $this->matcher->match('/', 'POST');
    }

    public function testMatchesReturnsTrue(): void
    {
        $this->assertTrue($this->matcher->matches('/', 'GET'));
        $this->assertTrue($this->matcher->matches('/users', 'GET'));
        $this->assertTrue($this->matcher->matches('/users/123', 'GET'));
    }

    public function testMatchesReturnsFalse(): void
    {
        $this->assertFalse($this->matcher->matches('/not-found', 'GET'));
        $this->assertFalse($this->matcher->matches('/', 'POST'));
    }

    public function testMatchWithTrailingSlash(): void
    {
        $result = $this->matcher->match('users/', 'GET');
        $this->assertEquals('users.index', $result['route']->getName());
    }

    public function testMatchWithLeadingSlash(): void
    {
        $result = $this->matcher->match('/users', 'GET');
        $this->assertEquals('users.index', $result['route']->getName());
    }

    public function testMatchCaseInsensitiveMethod(): void
    {
        $result = $this->matcher->match('/users', 'get');
        $this->assertEquals('users.index', $result['route']->getName());
    }

    protected function setUp(): void
    {
        $this->router = new Router();
        $this->matcher = new UrlMatcher($this->router);

        // Add test routes
        $this->router->get('/', fn (): string => 'home')->name('home');
        $this->router->get('/users', fn (): string => 'users')->name('users.index');
        $this->router->get('/users/{id}', fn ($id): string => 'user ' . $id)->name('users.show');
        $this->router->post('/users', fn (): string => 'create')->name('users.store');
        $this->router->get('/posts/{year}/{month}/{slug}', fn ($y, $m, $s): string => 'post')
            ->name('posts.show');
    }
}
