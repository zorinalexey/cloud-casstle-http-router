<?php

declare(strict_types = 1);

use CloudCastle\Http\Router\Loader\XmlLoader;
use CloudCastle\Http\Router\Loader\YamlLoader;
use CloudCastle\Http\Router\RouteDumper;
use CloudCastle\Http\Router\Router;

require_once __DIR__ . '/../vendor/autoload.php';

// Create router
$router = new Router();

// Example 1: Load from YAML
echo "=== Loading from YAML ===\n";
try {
    $yamlLoader = new YamlLoader($router);
    $yamlLoader->load(__DIR__ . '/yaml-routes.yaml');
    echo "✓ YAML routes loaded\n";
} catch (Exception $e) {
    echo "✗ YAML loading failed: {$e->getMessage()}\n";
}

// Example 2: Load from XML
echo "\n=== Loading from XML ===\n";
$xmlRouter = new Router();
try {
    $xmlLoader = new XmlLoader($xmlRouter);
    $xmlLoader->load(__DIR__ . '/xml-routes.xml');
    echo "✓ XML routes loaded\n";
} catch (Exception $e) {
    echo "✗ XML loading failed: {$e->getMessage()}\n";
}

// Example 3: Dump routes
echo "\n=== Route Dumper ===\n";
$dumper = new RouteDumper($router);

echo "\nJSON Format:\n";
echo $dumper->dumpJson();

echo "\n\nTable Format:\n";
echo $dumper->dumpTable();

// Example 4: Test defaults
echo "\n=== Testing Defaults ===\n";
$router->get('/page/{num}', fn ($num) => "Page {$num}")
    ->name('page')
    ->default('num', 1);

echo "Route /page with default num=1\n";
echo "Dispatching /page: ";
try {
    $result = $router->dispatch('/page', 'GET');
    echo "OK\n";
} catch (Exception $e) {
    echo "Failed: {$e->getMessage()}\n";
}

