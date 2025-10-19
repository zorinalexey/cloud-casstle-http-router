<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use CloudCastle\Http\Router\Router;
use CloudCastle\Http\Router\Loader\JsonLoader;

// Create router
$router = new Router();

// Create JSON loader
$loader = new JsonLoader($router);

// Load routes from JSON file
$loader->load(__DIR__ . '/json-routes.json');

// Dispatch request
try {
    $result = $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
    echo "Route matched: " . json_encode($result, JSON_PRETTY_PRINT);
} catch (\Exception $e) {
    http_response_code(404);
    echo "Error: " . $e->getMessage();
}

