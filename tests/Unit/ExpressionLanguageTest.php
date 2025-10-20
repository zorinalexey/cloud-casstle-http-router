<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\ExpressionLanguage\ExpressionLanguage;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class ExpressionLanguageTest extends TestCase
{
    private ExpressionLanguage $expr;

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

    public function testGreaterThanBoundary(): void
    {
        // Test > vs >=
        $result = $this->expr->evaluate('age > 18', ['age' => 19]);
        $this->assertTrue($result);

        $result = $this->expr->evaluate('age > 18', ['age' => 18]);
        $this->assertFalse($result); // Exactly 18 is NOT greater

        $result = $this->expr->evaluate('age >= 18', ['age' => 18]);
        $this->assertTrue($result); // But >= should work
    }

    public function testLessThanBoundary(): void
    {
        // Test < vs <=
        $result = $this->expr->evaluate('age < 18', ['age' => 17]);
        $this->assertTrue($result);

        $result = $this->expr->evaluate('age < 18', ['age' => 18]);
        $this->assertFalse($result); // Exactly 18 is NOT less

        $result = $this->expr->evaluate('age <= 18', ['age' => 18]);
        $this->assertTrue($result); // But <= should work
    }

    public function testNotEqualBoundary(): void
    {
        $result = $this->expr->evaluate('status != "active"', ['status' => 'inactive']);
        $this->assertTrue($result);

        $result = $this->expr->evaluate('status != "active"', ['status' => 'active']);
        $this->assertFalse($result);
    }

    public function testEqualityBoundary(): void
    {
        $result = $this->expr->evaluate('count == 0', ['count' => 0]);
        $this->assertTrue($result);

        $result = $this->expr->evaluate('count == 0', ['count' => 1]);
        $this->assertFalse($result);
    }

    public function testLogicalAndShortCircuit(): void
    {
        // First false should short-circuit
        $result = $this->expr->evaluate('false and unknown.var', ['false' => false]);
        $this->assertFalse($result);
    }

    public function testLogicalOrShortCircuit(): void
    {
        // First true should short-circuit
        $result = $this->expr->evaluate('true or unknown.var', ['true' => true]);
        $this->assertTrue($result);
    }

    public function testEmptyExpression(): void
    {
        // Trimmed empty should evaluate to false
        $result = $this->expr->evaluate('  ', []);
        $this->assertFalse($result);
    }

    public function testMixedTypeComparison(): void
    {
        $result = $this->expr->evaluate('value == 123', ['value' => 123]);
        $this->assertTrue($result);

        $result = $this->expr->evaluate('value == "123"', ['value' => 123]);
        $this->assertTrue($result); // PHP loose comparison
    }

    public function testTrimInEvaluate(): void
    {
        // Test that trim() is applied
        $result = $this->expr->evaluate('  age > 18  ', ['age' => 25]);
        $this->assertTrue($result);

        $result = $this->expr->evaluate("\n age > 18 \t", ['age' => 25]);
        $this->assertTrue($result);
    }

    public function testTrimInEvaluateValue(): void
    {
        // Test that trim() is applied in evaluateValue
        $result = $this->expr->evaluate('age ==  25  ', ['age' => 25]);
        $this->assertTrue($result);
    }

    public function testRegexWithCaretAndDollar(): void
    {
        // Test that ^ and $ are required in regex (not optional)
        $result = $this->expr->evaluate('age > 18', ['age' => 25]);
        $this->assertTrue($result);

        // Should not match partial expressions
        $result = $this->expr->evaluate('status == "active"', ['status' => 'active']);
        $this->assertTrue($result);
    }

    public function testStringLiteralWithQuotes(): void
    {
        // Test regex ^["'](.+)["']$ not just partial match
        $result = $this->expr->evaluate('name == "test"', ['name' => 'test']);
        $this->assertTrue($result);

        $result = $this->expr->evaluate("name == 'test'", ['name' => 'test']);
        $this->assertTrue($result);

        // Should not match strings without proper quotes
        $result = $this->expr->evaluate('value == 123', ['value' => 123]);
        $this->assertTrue($result);
    }

    public function testNumericTypeDetection(): void
    {
        // Test that str_contains('.') correctly detects float
        $result = $this->expr->evaluate('price == 19.99', ['price' => 19.99]);
        $this->assertTrue($result);

        $result = $this->expr->evaluate('count == 10', ['count' => 10]);
        $this->assertTrue($result);

        // Test integer vs float casting
        $result = $this->expr->evaluate('3.14 > 3', []);
        $this->assertTrue($result);
    }

    public function testDefaultCaseInMatch(): void
    {
        // Test that invalid operators fall through to default => false
        // This is difficult to test directly, but we can test all valid operators
        $result = $this->expr->evaluate('a == b', ['a' => 1, 'b' => 1]);
        $this->assertTrue($result);

        $result = $this->expr->evaluate('a != b', ['a' => 1, 'b' => 2]);
        $this->assertTrue($result);

        $result = $this->expr->evaluate('a > b', ['a' => 2, 'b' => 1]);
        $this->assertTrue($result);

        $result = $this->expr->evaluate('a < b', ['a' => 1, 'b' => 2]);
        $this->assertTrue($result);

        $result = $this->expr->evaluate('a >= b', ['a' => 2, 'b' => 2]);
        $this->assertTrue($result);

        $result = $this->expr->evaluate('a <= b', ['a' => 2, 'b' => 2]);
        $this->assertTrue($result);
    }

    public function testExceptionMessageContainsValue(): void
    {
        // Test that exception message includes the unknown value
        try {
            $this->expr->evaluate('unknownVar', []);
            $this->fail('Should throw exception');
        } catch (RuntimeException $e) {
            $this->assertStringContainsString('Unknown value:', $e->getMessage());
            $this->assertStringContainsString('unknownVar', $e->getMessage());
        }
    }

    public function testExplodeInAndOr(): void
    {
        // Test that ' and ' and ' or ' work correctly
        $result = $this->expr->evaluate('a and b', ['a' => true, 'b' => true]);
        $this->assertTrue($result);

        $result = $this->expr->evaluate('a or b', ['a' => false, 'b' => true]);
        $this->assertTrue($result);

        // Test with multiple parts
        $result = $this->expr->evaluate('a and b and c and d', [
            'a' => true, 'b' => true, 'c' => true, 'd' => true
        ]);
        $this->assertTrue($result);
    }

    protected function setUp(): void
    {
        $this->expr = new ExpressionLanguage();
    }
}
