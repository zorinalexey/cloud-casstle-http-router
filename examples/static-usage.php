<?php

declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

use CloudCastle\Http\Router\Exceptions\IpNotAllowedException;
use CloudCastle\Http\Router\Exceptions\MethodNotAllowedException;
use CloudCastle\Http\Router\Exceptions\RouteNotFoundException;
use CloudCastle\Http\Router\Facade\Route;

// ============================================
// Пример использования статического API
// ============================================

// Включение кеширования
Route::enableCache(__DIR__ . '/../cache');

// Попытка загрузить из кеша
if (!Route::loadFromCache()) {
    // Кеш не найден, регистрируем маршруты
    
    // Простые маршруты
    Route::get('/', function (){
        return 'Home Page';
    })->name('home');
    
    Route::get('/about', function (){
        return 'About Page';
    })->name('about');
    
    // Маршрут с параметрами
    Route::get('/users/{id:\d+}', function ($id){
        return "User ID: {$id}";
    })->name('users.show');
    
    // POST маршрут
    Route::post('/users', function (){
        return 'Create User';
    })->name('users.create');
    
    // Группы маршрутов
    Route::group(['prefix' => '/api/v1'], function (){
        Route::get('/users', function (){
            return json_encode(['users' => []]);
        })->tag('api');
        
        Route::get('/posts', function (){
            return json_encode(['posts' => []]);
        })->tag('api');
        
        // Вложенная группа с middleware
        Route::group(['middleware' => 'auth'], function (){
            Route::post('/users', function (){
                return json_encode(['message' => 'User created']);
            })->tag(['api', 'protected']);
            
            Route::delete('/users/{id}', function ($id){
                return json_encode(['message' => "User {$id} deleted"]);
            })->tag(['api', 'protected']);
        });
    });
    
    // Админ панель с IP фильтрацией
    Route::group([
        'prefix' => '/admin',
        'middleware' => 'admin',
        'whitelistIp' => ['127.0.0.1', '::1'],
    ], function (){
        Route::get('/dashboard', function (){
            return 'Admin Dashboard';
        })->name('admin.dashboard');
        
        Route::get('/settings', function (){
            return 'Admin Settings';
        })->name('admin.settings');
    });
    
    // Все HTTP методы
    Route::match(['GET', 'POST'], '/form', function (){
        return 'Form Page';
    });
    
    Route::any('/webhook', function (){
        return 'Webhook endpoint';
    });
    
    // Автокомпиляция при завершении
    register_shutdown_function([Route::class, 'autoCompile']);
}

// ============================================
// Обработка запроса
// ============================================

// Получение данных запроса (в реальном приложении используйте реальные данные)
$uri = $_SERVER['REQUEST_URI'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$domain = $_SERVER['HTTP_HOST'] ?? null;
$clientIp = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

// Очистка URI от query string
$uri = strtok($uri, '?');

try {
    // Диспатчинг запроса
    $route = Route::dispatch($uri, $method, $domain, $clientIp);
    
    echo "Route matched!\n";
    echo "URI: {$route->getUri()}\n";
    echo "Method: " . implode(', ', $route->getMethods()) . "\n";
    echo "Name: {$route->getName()}\n";
    echo "Parameters: " . json_encode($route->getParameters()) . "\n";
    
    // Выполнение action
    $action = $route->getAction();
    if ($action instanceof Closure) {
        $result = call_user_func_array($action, $route->getParameters());
        echo "\nResult: {$result}\n";
    }
} catch (RouteNotFoundException $e) {
    http_response_code(404);
    echo "404 - Route not found: {$e->getMessage()}\n";
} catch (MethodNotAllowedException $e) {
    http_response_code(405);
    echo "405 - Method not allowed\n";
    echo "Allowed methods: " . implode(', ', $e->getAllowedMethods()) . "\n";
} catch (IpNotAllowedException $e) {
    http_response_code(403);
    echo "403 - IP not allowed: {$e->getMessage()}\n";
} catch (Exception $e) {
    http_response_code(500);
    echo "500 - Error: {$e->getMessage()}\n";
}

// ============================================
// Статистика
// ============================================

echo "\n--- Router Statistics ---\n";
echo "Total routes: " . count(Route::getRoutes()) . "\n";
echo "Named routes: " . count(Route::getNamedRoutes()) . "\n";
echo "Cache loaded: " . (Route::isCacheLoaded() ? 'Yes' : 'No') . "\n";

// Получение маршрутов по тегу
$apiRoutes = Route::getRoutesByTag('api');
echo "API routes: " . count($apiRoutes) . "\n";

