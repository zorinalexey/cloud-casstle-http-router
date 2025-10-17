<?php

declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

// ============================================
// Примеры использования глобальных хелперов
// ============================================

echo "===============================================\n";
echo "Helper Functions Usage Examples\n";
echo "===============================================\n\n";

// Настройка маршрутов
Route::get('/', fn () => 'Home')->name('home');
Route::get('/users', fn () => 'Users')->name('users.index');
Route::get('/users/{id}', fn ($id) => "User {$id}")->name('users.show');
Route::get('/posts', fn () => 'Posts')->name('posts.index');
Route::get('/about', fn () => 'About')->name('about');

// ============================================
// 1. route() - Получение маршрута по имени
// ============================================
echo "1. route() function:\n";
echo str_repeat("-", 50) . "\n";

$userRoute = route('users.show');
if ($userRoute) {
    echo "Route found: {$userRoute->getUri()}\n";
    echo "Methods: " . implode(', ', $userRoute->getMethods()) . "\n";
}

// Без параметров - получить текущий маршрут
Route::dispatch('/users', 'GET');
$current = route();
echo "Current route: {$current?->getName()}\n\n";

// ============================================
// 2. current_route() - Текущий маршрут
// ============================================
echo "2. current_route():\n";
echo str_repeat("-", 50) . "\n";

$currentRoute = current_route();
echo "Current: {$currentRoute?->getName()}\n";
echo "URI: {$currentRoute?->getUri()}\n\n";

// ============================================
// 3. route_is() - Проверка имени маршрута
// ============================================
echo "3. route_is():\n";
echo str_repeat("-", 50) . "\n";

if (route_is('users.index')) {
    echo "✓ We are on users index page\n";
}

if (!route_is('home')) {
    echo "✗ We are NOT on home page\n";
}
echo "\n";

// ============================================
// 4. route_name() - Имя текущего маршрута
// ============================================
echo "4. route_name():\n";
echo str_repeat("-", 50) . "\n";

echo "Current route name: " . route_name() . "\n\n";

// ============================================
// 5. route_url() - Генерация URL
// ============================================
echo "5. route_url():\n";
echo str_repeat("-", 50) . "\n";

$url = route_url('users.show', ['id' => 123]);
echo "Generated URL: {$url}\n";

$postsUrl = route_url('posts.index');
echo "Posts URL: {$postsUrl}\n\n";

// ============================================
// 6. route_has() - Проверка существования
// ============================================
echo "6. route_has():\n";
echo str_repeat("-", 50) . "\n";

echo "Has 'home' route? " . (route_has('home') ? 'Yes' : 'No') . "\n";
echo "Has 'fake' route? " . (route_has('fake') ? 'Yes' : 'No') . "\n\n";

// ============================================
// 7. router() - Получение инстанса роутера
// ============================================
echo "7. router():\n";
echo str_repeat("-", 50) . "\n";

$router = router();
echo "Total routes: {$router->count()}\n";
echo "Named routes: " . count($router->getNamedRoutes()) . "\n\n";

// ============================================
// 8. route_stats() - Статистика
// ============================================
echo "8. route_stats():\n";
echo str_repeat("-", 50) . "\n";

$stats = route_stats();
echo "Total routes: {$stats['total']}\n";
echo "Named routes: {$stats['named']}\n";
echo "GET routes: {$stats['by_method']['GET']}\n\n";

// ============================================
// 9. previous_route() и route_back()
// ============================================
echo "9. previous_route() & route_back():\n";
echo str_repeat("-", 50) . "\n";

Route::dispatch('/posts', 'GET');
$prev = previous_route();
echo "Previous route: {$prev?->getName()}\n";
echo "Back URL: " . route_back() . "\n\n";

// ============================================
// 10. routes_by_tag() - Маршруты по тегу
// ============================================
echo "10. routes_by_tag():\n";
echo str_repeat("-", 50) . "\n";

Route::get('/api/v1/users', fn () => '')->tag('api');
Route::get('/api/v1/posts', fn () => '')->tag('api');

$apiRoutes = routes_by_tag('api');
echo "API routes: " . count($apiRoutes) . "\n\n";

// ============================================
// 11. Практические примеры
// ============================================
echo "11. Practical Examples:\n";
echo str_repeat("-", 50) . "\n\n";

// Пример 1: Breadcrumbs
echo "Breadcrumbs:\n";
$breadcrumbs = [
    '<a href="/">Home</a>',
];

if ($prev = previous_route()) {
    $breadcrumbs[] = '<a href="' . $prev->getUri() . '">' . $prev->getName() . '</a>';
}

if ($curr = current_route()) {
    $breadcrumbs[] = '<strong>' . $curr->getName() . '</strong>';
}

echo implode(' > ', $breadcrumbs) . "\n\n";

// Пример 2: Кнопка "Назад"
echo "Back button:\n";
echo '<a href="' . route_back('/') . '" class="btn">← Back</a>' . "\n\n";

// Пример 3: Условный рендеринг
echo "Conditional rendering:\n";
if (route_is('posts.index')) {
    echo "Showing posts index content\n";
}

// Пример 4: Активный пункт меню
echo "\nActive menu detection:\n";
$menuItems = ['home', 'users.index', 'posts.index', 'about'];
foreach ($menuItems as $item) {
    $active = route_is($item) ? ' [ACTIVE]' : '';
    echo "- {$item}{$active}\n";
}

echo "\n✓ All helper functions demonstrated!\n";

