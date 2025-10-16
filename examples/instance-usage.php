<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use CloudCastle\Http\Router\Router;

// ============================================
// Пример использования через экземпляр класса
// ============================================

// Создание экземпляра роутера
$router = new Router();

// Включение кеширования
$router->enableCache(__DIR__ . '/../cache');

// Попытка загрузить из кеша
if (!$router->loadFromCache()) {
    // Кеш не найден, регистрируем маршруты

    // Простые маршруты
    $router->get('/', function () {
        return 'Home Page';
    })->name('home');

    $router->get('/about', function () {
        return 'About Page';
    })->name('about');

    // Маршрут с параметрами
    $router->get('/users/{id:\d+}', function ($id) {
        return "User ID: {$id}";
    })->name('users.show');

    // POST маршрут
    $router->post('/users', function () {
        return 'Create User';
    })->name('users.create');

    // Группы маршрутов
    $router->group(['prefix' => '/api/v1'], function ($router) {
        $router->get('/users', function () {
            return json_encode(['users' => []]);
        })->tag('api');

        $router->get('/posts', function () {
            return json_encode(['posts' => []]);
        })->tag('api');
    });

    // Автокомпиляция при завершении
    register_shutdown_function([$router, 'autoCompile']);
}

// ============================================
// Обработка запроса
// ============================================

$uri = $_SERVER['REQUEST_URI'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = strtok($uri, '?');

try {
    $route = $router->dispatch($uri, $method);

    echo "Route matched!\n";
    echo "URI: {$route->getUri()}\n";
    echo "Parameters: " . json_encode($route->getParameters()) . "\n";

    $action = $route->getAction();
    if ($action instanceof Closure) {
        $result = call_user_func_array($action, $route->getParameters());
        echo "\nResult: {$result}\n";
    }
} catch (\Exception $e) {
    echo "Error: {$e->getMessage()}\n";
}

