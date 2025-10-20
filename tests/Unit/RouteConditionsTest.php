<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\Router;
use PHPUnit\Framework\TestCase;

class RouteConditionsTest extends TestCase
{
    private Router $router;

    public function testSetCondition(): void
    {
        $route = $this->router->get('/admin', fn (): string => 'admin')
            ->condition('role == "admin"');

        $this->assertEquals('role == "admin"', $route->getCondition());
    }

    public function testConditionFluentInterface(): void
    {
        $route = $this->router->get('/test', fn (): string => 'test')
            ->condition('age > 18')
            ->name('test')
            ->middleware(['auth']);

        $this->assertEquals('age > 18', $route->getCondition());
        $this->assertEquals('test', $route->getName());
        $this->assertEquals(['auth'], $route->getMiddleware());
    }

    public function testMultipleConditions(): void
    {
        $route = $this->router->get('/premium', fn (): string => 'premium')
            ->condition('subscription == "premium" and verified');

        $this->assertEquals('subscription == "premium" and verified', $route->getCondition());
    }

    public function testConditionCanBeOverridden(): void
    {
        $route = $this->router->get('/test', fn (): string => 'test')
            ->condition('old condition')
            ->condition('new condition');

        $this->assertEquals('new condition', $route->getCondition());
    }

    public function testNoCondition(): void
    {
        $route = $this->router->get('/test', fn (): string => 'test');

        $this->assertNull($route->getCondition());
    }

    public function testComplexCondition(): void
    {
        $route = $this->router->get('/api', fn (): string => 'api')
            ->condition('api_version >= 2 and authenticated and rate_limit < 100');

        $this->assertEquals(
            'api_version >= 2 and authenticated and rate_limit < 100',
            $route->getCondition()
        );
    }

    public function testConditionWithOr(): void
    {
        $route = $this->router->get('/access', fn (): string => 'access')
            ->condition('is_admin or is_moderator or is_owner');

        $this->assertEquals('is_admin or is_moderator or is_owner', $route->getCondition());
    }

    public function testConditionWithStringComparison(): void
    {
        $route = $this->router->get('/locale', fn (): string => 'locale')
            ->condition('locale == "en" or locale == "fr"');

        $this->assertEquals('locale == "en" or locale == "fr"', $route->getCondition());
    }

    public function testConditionWithNumbers(): void
    {
        $route = $this->router->get('/score', fn (): string => 'score')
            ->condition('score >= 100 and score <= 1000');

        $this->assertEquals('score >= 100 and score <= 1000', $route->getCondition());
    }

    public function testConditionReturnedByGetter(): void
    {
        $condition = 'user.age > 18 and user.verified';
        $route = $this->router->get('/test', fn (): string => 'test')
            ->condition($condition);

        $this->assertEquals($condition, $route->getCondition());
    }

    protected function setUp(): void
    {
        $this->router = new Router();
    }
}
