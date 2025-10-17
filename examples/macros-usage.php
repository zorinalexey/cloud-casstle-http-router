<?php

declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

// ============================================
// Примеры использования макросов
// ============================================

echo "===============================================\n";
echo "Route Macros Usage Examples\n";
echo "===============================================\n\n";

// ============================================
// 1. RESTful Resource - 7 маршрутов одной командой!
// ============================================
echo "1. RESTful Resource:\n";
echo str_repeat("-", 50) . "\n";

Route::resource('users', 'UserController');

echo "Created routes:\n";
echo "  GET    /users              → UserController@index   (user.index)\n";
echo "  GET    /users/create       → UserController@create  (user.create)\n";
echo "  POST   /users              → UserController@store   (user.store)\n";
echo "  GET    /users/{userId}     → UserController@show    (user.show)\n";
echo "  GET    /users/{userId}/edit → UserController@edit   (user.edit)\n";
echo "  PUT    /users/{userId}     → UserController@update  (user.update)\n";
echo "  DELETE /users/{userId}     → UserController@destroy (user.destroy)\n\n";

// ============================================
// 2. API Resource - RESTful API за одну строку!
// ============================================
echo "2. API Resource:\n";
echo str_repeat("-", 50) . "\n";

Route::apiResource('posts', 'Api\PostController', 200);

echo "Created API routes with:\n";
echo "  - API middleware\n";
echo "  - Rate limiting (200 req/min for reads, 100 for writes)\n";
echo "  - Tag 'api'\n";
echo "  - Named routes\n\n";

// ============================================
// 3. CRUD - Простые CRUD операции
// ============================================
echo "3. CRUD Routes:\n";
echo str_repeat("-", 50) . "\n";

Route::crud('comments', 'CommentController');

echo "Created CRUD routes:\n";
echo "  GET    /comments         → CommentController@index\n";
echo "  POST   /comments         → CommentController@create\n";
echo "  PUT    /comments/{id}    → CommentController@update\n";
echo "  DELETE /comments/{id}    → CommentController@delete\n\n";

// ============================================
// 4. Auth Routes - Все маршруты аутентификации
// ============================================
echo "4. Auth Routes:\n";
echo str_repeat("-", 50) . "\n";

Route::auth();

echo "Created auth routes:\n";
echo "  - Login (GET/POST) with rate limiting\n";
echo "  - Logout (POST)\n";
echo "  - Register (GET/POST) with strict rate limiting\n";
echo "  - Password reset with throttling\n\n";

// ============================================
// 5. Admin Panel - Админка одной строкой
// ============================================
echo "5. Admin Panel:\n";
echo str_repeat("-", 50) . "\n";

Route::adminPanel(['192.168.1.1', '127.0.0.1']);

echo "Created admin panel with:\n";
echo "  - /admin prefix\n";
echo "  - Auth + Admin middleware\n";
echo "  - IP whitelist\n";
echo "  - Dashboard, users, settings routes\n\n";

// ============================================
// 6. API Versioning - Версионирование API
// ============================================
echo "6. API Versioning:\n";
echo str_repeat("-", 50) . "\n";

Route::apiVersion('v1', function (){
    Route::get('/users', 'Api\V1\UserController@index');
    Route::get('/posts', 'Api\V1\PostController@index');
});

Route::apiVersion('v2', function (){
    Route::get('/users', 'Api\V2\UserController@index');
    Route::get('/posts', 'Api\V2\PostController@index');
});

echo "Created versioned API routes:\n";
echo "  - /api/v1/* with api middleware\n";
echo "  - /api/v2/* with api middleware\n";
echo "  - Rate limiting and tagging\n\n";

// ============================================
// 7. Webhooks - Безопасные webhook endpoints
// ============================================
echo "7. Webhooks:\n";
echo str_repeat("-", 50) . "\n";

Route::webhooks(['192.0.2.1', '198.51.100.1']);

echo "Created webhook routes with:\n";
echo "  - /webhooks prefix\n";
echo "  - Signature verification middleware\n";
echo "  - High rate limit (1000 req/min)\n";
echo "  - IP whitelist\n\n";

// ============================================
// Статистика
// ============================================
echo "===============================================\n";
echo "Statistics\n";
echo "===============================================\n\n";

$stats = Route::getRouteStats();
echo "Total routes created: {$stats['total']}\n";
echo "Named routes: {$stats['named']}\n";
echo "With middleware: {$stats['with_middleware']}\n";
echo "Throttled: {$stats['throttled']}\n";

echo "\nBy Method:\n";
foreach ($stats['by_method'] as $method => $count) {
    if ($count > 0) {
        echo "  {$method}: {$count}\n";
    }
}

echo "\n✓ Macros reduce code by 80% for common patterns!\n\n";

// ============================================
// Сравнение: Обычный способ vs Макросы
// ============================================
echo "===============================================\n";
echo "Comparison: Manual vs Macros\n";
echo "===============================================\n\n";

echo "MANUAL WAY (35+ lines):\n";
echo "```php\n";
echo "Route::get('/products', 'ProductController@index')->name('product.index');\n";
echo "Route::get('/products/create', 'ProductController@create')->name('product.create');\n";
echo "Route::post('/products', 'ProductController@store')->name('product.store');\n";
echo "Route::get('/products/{productId}', 'ProductController@show')->name('product.show');\n";
echo "Route::get('/products/{productId}/edit', 'ProductController@edit')->name('product.edit');\n";
echo "Route::put('/products/{productId}', 'ProductController@update')->name('product.update');\n";
echo "Route::delete('/products/{productId}', 'ProductController@destroy')->name('product.destroy');\n";
echo "```\n\n";

echo "WITH MACRO (1 line):\n";
echo "```php\n";
echo "Route::resource('products', 'ProductController');\n";
echo "```\n\n";

echo "✓ 35x less code!\n";
echo "✓ No typos in route names\n";
echo "✓ RESTful conventions enforced\n";
echo "✓ Consistent structure\n";

