<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\ExpressionLanguage;

use RuntimeException;

/**
 * Simple expression language for route conditions.
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
class ExpressionLanguage
{
    /**
     * Evaluate an expression with given context.
     *
     * @param array<string, mixed> $context
     */
    public function evaluate(string $expression, array $context = []): bool
    {
        // Support for common operators
        $expression = trim($expression);
        
        // Handle empty expression
        if ($expression === '') {
            return false;
        }

        // Support logical AND/OR (process first to handle complex expressions)
        if (str_contains($expression, ' and ')) {
            $parts = explode(' and ', $expression);
            foreach ($parts as $part) {
                if (!$this->evaluate($part, $context)) {
                    return false;
                }
            }

            return true;
        }

        if (str_contains($expression, ' or ')) {
            $parts = explode(' or ', $expression);
            foreach ($parts as $part) {
                if ($this->evaluate($part, $context)) {
                    return true;
                }
            }

            return false;
        }

        // Support common comparisons (order matters: >= and <= before > and <)
        if (preg_match('/^(.+?)\s*(==|!=|>=|<=|>|<)\s*(.+)$/', $expression, $matches) === 1) {
            $left = $this->evaluateValue($matches[1], $context);
            $operator = $matches[2];
            $right = $this->evaluateValue($matches[3], $context);

            return match ($operator) {
                '==' => $left == $right,
                '!=' => $left != $right,
                '>' => $left > $right,
                '>=' => $left >= $right,
                '<' => $left < $right,
                '<=' => $left <= $right,
                default => false,
            };
        }

        // Direct boolean evaluation
        return (bool) $this->evaluateValue($expression, $context);
    }

    /**
     * Evaluate a single value.
     *
     * @param array<string, mixed> $context
     *
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    private function evaluateValue(string $value, array $context): mixed
    {
        $value = trim($value);

        // String literals
        if (preg_match('/^["\'](.+)["\']$/', $value, $matches)) {
            return $matches[1];
        }

        // Numbers
        if (is_numeric($value)) {
            return str_contains($value, '.') ? (float) $value : (int) $value;
        }

        // Booleans
        if ($value === 'true') {
            return true;
        }

        if ($value === 'false') {
            return false;
        }

        // Variables from context
        if (isset($context[$value])) {
            return $context[$value];
        }

        // Try to extract from context using dot notation
        if (!str_contains($value, '.')) {
            throw new RuntimeException('Unknown value: ' . $value);
        }

        if (str_contains($value, '.')) {
            $parts = explode('.', $value);
            $current = $context;

            foreach ($parts as $part) {
                if (is_array($current) && isset($current[$part])) {
                    $current = $current[$part];
                } else {
                    return null;
                }
            }

            return $current;
        }

        throw new RuntimeException('Unknown value: ' . $value);
    }
}
