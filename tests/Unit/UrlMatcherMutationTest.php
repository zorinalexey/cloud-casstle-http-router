<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\Router;
use CloudCastle\Http\Router\UrlMatcher;
use CloudCastle\Http\Router\Exceptions\RouteNotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * Additional tests to kill UrlMatcher mutations.
 */
class UrlMatcherMutationTest extends TestCase
{
    private Router $router;
    private UrlMatcher $matcher;

    public function testMatchNormalizesUri(): void
    {
        $this->router->get('/users', fn () => 'users');

        // Test with leading slash
        $result = $this->matcher->match('/users', 'GET');
        $this->assertEquals('/users', $result['route']->getUri());

        // Test without leading slash
        $result2 = $this->matcher->match('users', 'GET');
        $this->assertEquals('/users', $result2['route']->getUri());
    }

    public function testMatchNormalizesUriInPattern(): void
    {
        // Route URI is normalized (trim slashes)
        $this->router->get('/api/users', fn () => 'api-users');

        $result = $this->matcher->match('/api/users', 'GET');
        $this->assertNotNull($result['route']);

        $result2 = $this->matcher->match('api/users', 'GET');
        $this->assertNotNull($result2['route']);
    }

    public function testMatchWithParameters(): void
    {
        $this->router->get('/users/{id}', fn ($id) => 'user');

        $result = $this->matcher->match('/users/123', 'GET');
        $this->assertArrayHasKey('parameters', $result);
        $this->assertEquals(['id' => '123'], $result['parameters']);
        $this->assertIsArray($result['parameters']);
        $this->assertNotEmpty($result['parameters']);
    }

    public function testMatchWithMultipleParameters(): void
    {
        $this->router->get('/posts/{year}/{month}/{slug}', fn () => 'post');

        $result = $this->matcher->match('/posts/2025/10/test', 'GET');
        $params = $result['parameters'];
        
        $this->assertCount(3, $params);
        $this->assertEquals('2025', $params['year']);
        $this->assertEquals('10', $params['month']);
        $this->assertEquals('test', $params['slug']);
    }

    public function testMatchMethodIsCaseInsensitive(): void
    {
        $this->router->get('/users', fn () => 'users');

        // Uppercase
        $result = $this->matcher->match('/users', 'GET');
        $this->assertNotNull($result['route']);

        // Lowercase should be converted
        $result2 = $this->matcher->match('/users', 'get');
        $this->assertNotNull($result2['route']);
    }

    public function testMatchesReturnsTrueForExistingRoute(): void
    {
        $this->router->get('/exists', fn () => 'exists');

        $this->assertTrue($this->matcher->matches('/exists', 'GET'));
        $this->assertFalse($this->matcher->matches('/notexists', 'GET'));
    }

    public function testMatchThrowsForNonExistentRoute(): void
    {
        $this->router->get('/exists', fn () => 'exists');

        $this->expectException(RouteNotFoundException::class);
        $this->expectExceptionMessage('No route matches URL: /nonexistent');

        $this->matcher->match('/nonexistent', 'GET');
    }

    public function testMatchExceptionMessageIncludesSlash(): void
    {
        $this->router->get('/test', fn () => 'test');

        try {
            $this->matcher->match('missing', 'GET');
            $this->fail('Should throw exception');
        } catch (RouteNotFoundException $e) {
            // Exception message should have / prefix
            $this->assertStringContainsString('/missing', $e->getMessage());
            $this->assertStringStartsWith('No route matches URL: /', $e->getMessage());
        }
    }

    public function testCompilePatternWithSlash(): void
    {
        // Test internal compilePattern with slash normalization
        $this->router->get('/users/{id}', fn ($id) => 'user');

        // Both should work due to normalization
        $result1 = $this->matcher->match('/users/123', 'GET');
        $result2 = $this->matcher->match('users/123', 'GET');

        $this->assertEquals($result1['parameters'], $result2['parameters']);
    }

    public function testMatchFiltersNonIntegerKeys(): void
    {
        $this->router->get('/users/{id}/posts/{postId}', fn () => 'test');

        $result = $this->matcher->match('/users/1/posts/2', 'GET');
        $params = $result['parameters'];

        // Should only have named parameters, not numeric indexes
        $this->assertArrayHasKey('id', $params);
        $this->assertArrayHasKey('postId', $params);
        $this->assertArrayNotHasKey(0, $params);
        $this->assertArrayNotHasKey(1, $params);
    }

    protected function setUp(): void
    {
        $this->router = new Router();
        $this->matcher = new UrlMatcher($this->router);
    }
}

