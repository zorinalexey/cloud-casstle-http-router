<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\ExpressionLanguage\ExpressionLanguage;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class ExpressionLanguageTest extends TestCase
{
    private ExpressionLanguage $expr;

    protected function setUp(): void
    {
        $this->expr = new ExpressionLanguage();
    }

    public function testSimpleEquality(): void
    {
        $result = $this->expr->evaluate('age == 25', ['age' => 25]);
        $this->assertTrue($result);

        $result = $this->expr->evaluate('age == 30', ['age' => 25]);
        $this->assertFalse($result);
    }

    public function testNotEqual(): void
    {
        $result = $this->expr->evaluate('age != 30', ['age' => 25]);
        $this->assertTrue($result);

        $result = $this->expr->evaluate('age != 25', ['age' => 25]);
        $this->assertFalse($result);
    }

    public function testGreaterThan(): void
    {
        $result = $this->expr->evaluate('age > 18', ['age' => 25]);
        $this->assertTrue($result);

        $result = $this->expr->evaluate('age > 30', ['age' => 25]);
        $this->assertFalse($result);
    }

    public function testLessThan(): void
    {
        $result = $this->expr->evaluate('age < 30', ['age' => 25]);
        $this->assertTrue($result);

        $result = $this->expr->evaluate('age < 18', ['age' => 25]);
        $this->assertFalse($result);
    }

    public function testGreaterThanOrEqual(): void
    {
        $result = $this->expr->evaluate('age >= 25', ['age' => 25]);
        $this->assertTrue($result);

        $result = $this->expr->evaluate('age >= 30', ['age' => 25]);
        $this->assertFalse($result);
    }

    public function testLessThanOrEqual(): void
    {
        $result = $this->expr->evaluate('age <= 25', ['age' => 25]);
        $this->assertTrue($result);

        $result = $this->expr->evaluate('age <= 18', ['age' => 25]);
        $this->assertFalse($result);
    }

    public function testStringComparison(): void
    {
        $result = $this->expr->evaluate('role == "admin"', ['role' => 'admin']);
        $this->assertTrue($result);

        $result = $this->expr->evaluate('role != "user"', ['role' => 'admin']);
        $this->assertTrue($result);
    }

    public function testLogicalAnd(): void
    {
        $result = $this->expr->evaluate('logged_in and is_admin', [
            'logged_in' => true,
            'is_admin' => true,
        ]);
        $this->assertTrue($result);

        $result = $this->expr->evaluate('logged_in and is_admin', [
            'logged_in' => true,
            'is_admin' => false,
        ]);
        $this->assertFalse($result);
    }

    public function testLogicalOr(): void
    {
        $result = $this->expr->evaluate('is_admin or is_moderator', [
            'is_admin' => false,
            'is_moderator' => true,
        ]);
        $this->assertTrue($result);

        $result = $this->expr->evaluate('is_admin or is_moderator', [
            'is_admin' => false,
            'is_moderator' => false,
        ]);
        $this->assertFalse($result);
    }

    public function testBooleanLiterals(): void
    {
        $this->assertTrue($this->expr->evaluate('true', []));
        $this->assertFalse($this->expr->evaluate('false', []));
    }

    public function testNumericValues(): void
    {
        $result = $this->expr->evaluate('10 > 5', []);
        $this->assertTrue($result);

        $result = $this->expr->evaluate('3.14 > 3', []);
        $this->assertTrue($result);
    }

    public function testDotNotation(): void
    {
        $result = $this->expr->evaluate('user.age > 18', [
            'user' => ['age' => 25],
        ]);
        $this->assertTrue($result);

        $result = $this->expr->evaluate('user.verified', [
            'user' => ['verified' => true],
        ]);
        $this->assertTrue($result);
    }

    public function testNestedDotNotation(): void
    {
        $result = $this->expr->evaluate('user.profile.age > 18', [
            'user' => [
                'profile' => [
                    'age' => 25,
                ],
            ],
        ]);
        $this->assertTrue($result);
    }

    public function testComplexExpression(): void
    {
        $result = $this->expr->evaluate('age > 18 and role == "admin"', [
            'age' => 25,
            'role' => 'admin',
        ]);
        $this->assertTrue($result);

        $result = $this->expr->evaluate('age > 18 and role == "user"', [
            'age' => 25,
            'role' => 'admin',
        ]);
        $this->assertFalse($result);
    }

    public function testMultipleAndConditions(): void
    {
        $result = $this->expr->evaluate('a and b and c', [
            'a' => true,
            'b' => true,
            'c' => true,
        ]);
        $this->assertTrue($result);

        $result = $this->expr->evaluate('a and b and c', [
            'a' => true,
            'b' => false,
            'c' => true,
        ]);
        $this->assertFalse($result);
    }

    public function testMultipleOrConditions(): void
    {
        $result = $this->expr->evaluate('a or b or c', [
            'a' => false,
            'b' => false,
            'c' => true,
        ]);
        $this->assertTrue($result);

        $result = $this->expr->evaluate('a or b or c', [
            'a' => false,
            'b' => false,
            'c' => false,
        ]);
        $this->assertFalse($result);
    }

    public function testUnknownVariable(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unknown value');

        $this->expr->evaluate('unknown_var', []);
    }

    public function testNullDotNotation(): void
    {
        $result = $this->expr->evaluate('user.missing.field == 1', [
            'user' => ['age' => 25],
        ]);

        $this->assertFalse($result);
    }
}
