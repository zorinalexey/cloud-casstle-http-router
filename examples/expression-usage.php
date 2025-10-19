<?php

declare(strict_types=1);

use CloudCastle\Http\Router\ExpressionLanguage\ExpressionLanguage;
use CloudCastle\Http\Router\Router;

require_once __DIR__ . '/../vendor/autoload.php';

$router = new Router();
$expr = new ExpressionLanguage();

echo "=== Expression Language Examples ===\n\n";

// Example 1: Simple comparisons
echo "1. Simple comparisons:\n";
$context1 = ['age' => 25];
echo "  age > 18: " . ($expr->evaluate('age > 18', $context1) ? 'true' : 'false') . "\n";
echo "  age == 25: " . ($expr->evaluate('age == 25', $context1) ? 'true' : 'false') . "\n";
echo "  age < 18: " . ($expr->evaluate('age < 18', $context1) ? 'true' : 'false') . "\n";

// Example 2: Logical operations
echo "\n2. Logical operations:\n";
$context2 = ['logged_in' => true, 'is_admin' => false];
echo "  logged_in and is_admin: " . ($expr->evaluate('logged_in and is_admin', $context2) ? 'true' : 'false') . "\n";
echo "  logged_in or is_admin: " . ($expr->evaluate('logged_in or is_admin', $context2) ? 'true' : 'false') . "\n";

// Example 3: String comparisons
echo "\n3. String comparisons:\n";
$context3 = ['role' => 'admin'];
echo "  role == \"admin\": " . ($expr->evaluate('role == "admin"', $context3) ? 'true' : 'false') . "\n";
echo "  role != \"user\": " . ($expr->evaluate('role != "user"', $context3) ? 'true' : 'false') . "\n";

// Example 4: Routes with conditions
echo "\n4. Routes with conditions:\n";

$router->get('/admin/dashboard', fn () => 'Admin Dashboard')
    ->name('admin.dashboard')
    ->condition('role == "admin" and logged_in');

$router->get('/api/data', fn () => 'API Data')
    ->name('api.data')
    ->condition('api_version >= 2');

$router->get('/premium/content', fn () => 'Premium Content')
    ->name('premium.content')
    ->condition('subscription == "premium" or subscription == "enterprise"');

foreach ($router->getAllRoutes() as $route) {
    if ($route->getCondition()) {
        echo "  Route: {$route->getName()}\n";
        echo "    Condition: {$route->getCondition()}\n";
        
        // Test conditions
        $testContexts = [
            'admin.dashboard' => [
                ['role' => 'admin', 'logged_in' => true],
                ['role' => 'user', 'logged_in' => true],
            ],
            'api.data' => [
                ['api_version' => 3],
                ['api_version' => 1],
            ],
            'premium.content' => [
                ['subscription' => 'premium'],
                ['subscription' => 'free'],
                ['subscription' => 'enterprise'],
            ],
        ];
        
        if (isset($testContexts[$route->getName()])) {
            foreach ($testContexts[$route->getName()] as $testContext) {
                $result = $expr->evaluate($route->getCondition(), $testContext);
                echo "      " . json_encode($testContext) . " => " . ($result ? '✓' : '✗') . "\n";
            }
        }
    }
}

// Example 5: Complex conditions
echo "\n5. Complex conditions:\n";
$complexContext = [
    'user' => [
        'age' => 30,
        'verified' => true,
        'subscription' => 'premium'
    ]
];

// Note: Dot notation support
echo "  Testing context: " . json_encode($complexContext) . "\n";
echo "  user.age > 25: " . ($expr->evaluate('user.age > 25', $complexContext) ? 'true' : 'false') . "\n";
echo "  user.verified: " . ($expr->evaluate('user.verified', $complexContext) ? 'true' : 'false') . "\n";

