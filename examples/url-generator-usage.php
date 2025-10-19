<?php

declare(strict_types=1);

use CloudCastle\Http\Router\Router;
use CloudCastle\Http\Router\UrlGenerator;
use CloudCastle\Http\Router\UrlMatcher;

require_once __DIR__ . '/../vendor/autoload.php';

// Create router and add routes
$router = new Router();

$router->get('/', fn () => 'Home')->name('home');
$router->get('/users', fn () => 'Users')->name('users.index');
$router->get('/users/{id}', fn ($id) => "User {$id}")->name('users.show');
$router->get('/posts/{year}/{month}/{slug}', fn ($y, $m, $s) => "Post {$s}")
    ->name('posts.show');

// URL Generator
echo "=== URL Generator ===\n\n";

$generator = new UrlGenerator($router);
$generator->setBaseUrl('https://example.com');

echo "home: " . $generator->generate('home') . "\n";
echo "users.index: " . $generator->generate('users.index') . "\n";
echo "users.show: " . $generator->generate('users.show', ['id' => 123]) . "\n";
echo "posts.show: " . $generator->generate('posts.show', [
    'year' => 2025,
    'month' => 10,
    'slug' => 'my-post'
]) . "\n";

echo "\nWith query params:\n";
echo "users.show: " . $generator->generate('users.show', ['id' => 123], ['edit' => 1, 'tab' => 'profile']) . "\n";

// URL Matcher
echo "\n\n=== URL Matcher ===\n\n";

$matcher = new UrlMatcher($router);

$tests = [
    ['/', 'GET'],
    ['/users', 'GET'],
    ['/users/456', 'GET'],
    ['/posts/2025/10/hello-world', 'GET'],
    ['/not-found', 'GET'],
];

foreach ($tests as [$url, $method]) {
    echo "Testing {$method} {$url}: ";
    
    try {
        $match = $matcher->match($url, $method);
        echo "✓ Matched {$match['route']->getName()}\n";
        if ($match['parameters'] !== []) {
            echo "  Parameters: " . json_encode($match['parameters']) . "\n";
        }
    } catch (Exception $e) {
        echo "✗ {$e->getMessage()}\n";
    }
}

// Check if URL matches
echo "\n\n=== Checking URL matches ===\n";
echo "/users exists: " . ($matcher->matches('/users', 'GET') ? 'YES' : 'NO') . "\n";
echo "/admin exists: " . ($matcher->matches('/admin', 'GET') ? 'YES' : 'NO') . "\n";

