<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\Router;
use CloudCastle\Http\Router\UrlGenerator;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class UrlGeneratorTest extends TestCase
{
    private Router $router;

    private UrlGenerator $generator;

    public function testGenerateSimpleRoute(): void
    {
        $url = $this->generator->generate('home');
        $this->assertEquals('/', $url);
    }

    public function testGenerateRouteWithoutParameters(): void
    {
        $url = $this->generator->generate('users.index');
        $this->assertEquals('/users', $url);
    }

    public function testGenerateRouteWithParameter(): void
    {
        $url = $this->generator->generate('users.show', ['id' => 123]);
        $this->assertEquals('/users/123', $url);
    }

    public function testGenerateRouteWithMultipleParameters(): void
    {
        $url = $this->generator->generate('posts.show', [
            'year' => 2025,
            'month' => 10,
            'slug' => 'my-post',
        ]);

        $this->assertEquals('/posts/2025/10/my-post', $url);
    }

    public function testGenerateWithQueryParameters(): void
    {
        $url = $this->generator->generate('users.show', ['id' => 123], ['edit' => 1, 'tab' => 'profile']);
        $this->assertEquals('/users/123?edit=1&tab=profile', $url);
    }

    public function testGenerateWithBaseUrl(): void
    {
        $this->generator->setBaseUrl('https://example.com');
        $url = $this->generator->generate('users.show', ['id' => 123]);

        $this->assertEquals('https://example.com/users/123', $url);
    }

    public function testGenerateAbsolute(): void
    {
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['HTTP_HOST'] = 'example.com';

        $url = $this->generator->absolute('users.show', ['id' => 123]);

        $this->assertStringContainsString('https://example.com/users/123', $url);
    }

    public function testGenerateNonExistentRoute(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Route not found: non.existent');

        $this->generator->generate('non.existent');
    }

    public function testGenerateMissingParameter(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Missing parameter: id');

        $this->generator->generate('users.show');
    }

    public function testSetBaseUrlReturnsInstance(): void
    {
        $result = $this->generator->setBaseUrl('https://example.com');
        $this->assertInstanceOf(UrlGenerator::class, $result);
    }

    public function testBaseUrlTrimsTrailingSlash(): void
    {
        $this->generator->setBaseUrl('https://example.com/');
        $url = $this->generator->generate('users.index');

        $this->assertEquals('https://example.com/users', $url);
    }

    public function testGenerateWithStringAndIntParameters(): void
    {
        $url = $this->generator->generate('posts.show', [
            'year' => '2025',
            'month' => 10,
            'slug' => 'test-slug',
        ]);

        $this->assertEquals('/posts/2025/10/test-slug', $url);
    }

    public function testGenerateEmptyQueryParameters(): void
    {
        $url = $this->generator->generate('users.show', ['id' => 123], []);
        $this->assertEquals('/users/123', $url);
        $this->assertStringNotContainsString('?', $url);
    }

    protected function setUp(): void
    {
        $this->router = new Router();
        $this->generator = new UrlGenerator($this->router);

        // Add test routes
        $this->router->get('/', fn (): string => 'home')->name('home');
        $this->router->get('/users', fn (): string => 'users')->name('users.index');
        $this->router->get('/users/{id}', fn ($id): string => 'user ' . $id)->name('users.show');
        $this->router->get('/posts/{year}/{month}/{slug}', fn ($y, $m, $s): string => 'post')
            ->name('posts.show');
    }
}
