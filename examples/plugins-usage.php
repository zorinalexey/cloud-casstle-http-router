<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use CloudCastle\Http\Router\Router;
use CloudCastle\Http\Router\Plugin\LoggerPlugin;
use CloudCastle\Http\Router\Plugin\AnalyticsPlugin;
use CloudCastle\Http\Router\Plugin\ResponseCachePlugin;

echo "=== CloudCastle Router - Plugin System ===\n\n";

// Create router instance
$router = Router::getInstance();

echo "1. Регистрация плагинов\n";
echo "------------------------\n";

// Logger Plugin
$logger = new LoggerPlugin('/tmp/router_example.log');
$router->registerPlugin($logger);
echo "✓ Logger plugin registered\n";

// Analytics Plugin
$analytics = new AnalyticsPlugin();
$router->registerPlugin($analytics);
echo "✓ Analytics plugin registered\n";

// Response Cache Plugin
$cache = new ResponseCachePlugin(300); // 5 minutes TTL
$cache->setCacheableRoutes(['users.list', 'posts.list']);
$router->registerPlugin($cache);
echo "✓ Response Cache plugin registered\n\n";

// List all plugins
echo "Registered plugins:\n";
foreach ($router->getPlugins() as $name => $plugin) {
    printf("  - %s (v%s) - %s\n", 
        $name, 
        $plugin->getVersion(),
        $plugin->isEnabled() ? 'enabled' : 'disabled'
    );
}
echo "\n";

echo "2. Создание маршрутов (логируются автоматически)\n";
echo "-------------------------------------------------\n";

$router->get('/users', function () {
    return ['user1', 'user2', 'user3'];
})->name('users.list');

$router->get('/users/{id}', function ($id) {
    return ['id' => $id, 'name' => "User $id"];
})->name('users.show');

$router->post('/users', function () {
    return ['status' => 'created'];
})->name('users.create');

$router->get('/posts', function () {
    return ['post1', 'post2'];
})->name('posts.list');

echo "✓ 4 routes registered\n";
echo "✓ Logger automatically logged all route registrations\n\n";

echo "3. Диспетчеризация маршрутов\n";
echo "-----------------------------\n";

// Simulate requests
$requests = [
    ['GET', '/users'],
    ['GET', '/users'],
    ['GET', '/users/123'],
    ['GET', '/posts'],
    ['GET', '/posts'],
    ['GET', '/posts'],
    ['POST', '/users'],
];

foreach ($requests as [$method, $uri]) {
    try {
        $route = $router->dispatch($uri, $method);
        // Extract parameters from route
        $parameters = $route->getParameters();
        $result = $router->executeRoute($route, $parameters);
        echo "✓ $method $uri -> " . json_encode($result) . "\n";
    } catch (\Exception $e) {
        echo "✗ $method $uri -> Error: " . $e->getMessage() . "\n";
    }
}

echo "\n";

echo "4. Analytics Plugin - Статистика\n";
echo "--------------------------------\n";

$stats = $analytics->getStatistics();

echo "Total dispatches: " . $stats['total_dispatches'] . "\n";
echo "Total routes registered: " . $stats['total_routes_registered'] . "\n";
echo "Total exceptions: " . $stats['total_exceptions'] . "\n";

echo "\nRoute hits:\n";
foreach ($stats['route_hits'] as $route => $hits) {
    echo "  $route: $hits hits\n";
}

echo "\nMethod stats:\n";
foreach ($stats['method_stats'] as $method => $count) {
    echo "  $method: $count requests\n";
}

echo "\nMost popular route: " . ($stats['most_popular_route'] ?? 'N/A') . "\n";
echo "Most used method: " . ($stats['most_used_method'] ?? 'N/A') . "\n";

if ($stats['average_execution_time'] > 0) {
    echo sprintf("Average execution time: %.4f seconds\n", $stats['average_execution_time']);
}

echo "\n";

echo "5. Response Cache Plugin - Кеш\n";
echo "-------------------------------\n";

$cacheStats = $cache->getCacheStats();

echo "Total cached responses: " . $cacheStats['total_cached'] . "\n";
echo "Active cache entries: " . $cacheStats['active'] . "\n";
echo "Expired entries: " . $cacheStats['expired'] . "\n";

echo "\nCached routes:\n";
foreach ($cacheStats['cache_keys'] as $key) {
    echo "  - Cache key: " . substr($key, 0, 16) . "...\n";
}

echo "\n";

echo "6. Logger Plugin - Просмотр логов\n";
echo "--------------------------------\n";

if (file_exists('/tmp/router_example.log')) {
    $logContent = file_get_contents('/tmp/router_example.log');
    $logLines = explode("\n", (string) $logContent);
    
    echo "Last 10 log entries:\n";
    $recentLogs = array_slice(array_filter($logLines), -10);
    
    foreach ($recentLogs as $logLine) {
        echo "  " . $logLine . "\n";
    }
} else {
    echo "Log file not found\n";
}

echo "\n";

echo "7. Управление плагинами\n";
echo "------------------------\n";

// Disable analytics
$analytics->disable();
echo "✓ Analytics plugin disabled\n";

// Make a request - won't be counted
$router->dispatch('/users', 'GET');

$newStats = $analytics->getStatistics();
echo "Dispatches after disable: " . $newStats['total_dispatches'] . " (same as before)\n";

// Re-enable
$analytics->enable();
echo "✓ Analytics plugin re-enabled\n";

// Unregister logger
$router->unregisterPlugin('logger');
echo "✓ Logger plugin unregistered\n";

echo "\nCurrent plugins:\n";
foreach ($router->getPlugins() as $name => $plugin) {
    echo "  - $name\n";
}

echo "\n";

echo "8. Кастомный плагин (пример)\n";
echo "-----------------------------\n";

// Create custom plugin
$customPlugin = new class extends \CloudCastle\Http\Router\Plugin\AbstractPlugin {
    private int $requestCount = 0;
    
    public function getName(): string
    {
        return 'custom_counter';
    }
    
    public function beforeDispatch(\CloudCastle\Http\Router\Route $route, string $uri, string $method): void
    {
        $this->requestCount++;
        echo "  [Custom Plugin] Request #{$this->requestCount}: $method $uri\n";
    }
    
    public function getRequestCount(): int
    {
        return $this->requestCount;
    }
};

$router->registerPlugin($customPlugin);
echo "✓ Custom plugin registered\n\n";

// Make some requests
$router->dispatch('/users', 'GET');
$router->dispatch('/posts', 'GET');

echo "\nCustom plugin counted: " . $customPlugin->getRequestCount() . " requests\n";

echo "\n";

echo "=== Summary ===\n";
echo "Plugins provide powerful extensibility!\n";
echo "Create custom plugins for:\n";
echo "  - Logging and monitoring\n";
echo "  - Analytics and metrics\n";
echo "  - Caching and optimization\n";
echo "  - Custom business logic\n";
echo "  - Integration with external services\n";
echo "\nAll without modifying core router code! 🚀\n";

// Cleanup
@unlink('/tmp/router_example.log');

