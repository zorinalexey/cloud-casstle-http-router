<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use CloudCastle\Http\Router\Facade\Route;

// ============================================
// Пример работы с навигацией и историей маршрутов
// ============================================

// Настройка маршрутов
Route::get('/', fn() => 'Home')->name('home');
Route::get('/products', fn() => 'Products')->name('products.index');
Route::get('/products/{id}', fn($id) => "Product {$id}")->name('products.show');
Route::get('/cart', fn() => 'Cart')->name('cart');
Route::get('/checkout', fn() => 'Checkout')->name('checkout');
Route::get('/thank-you', fn() => 'Thank You')->name('thank-you');

echo "===============================================\n";
echo "Navigation & Route History Example\n";
echo "===============================================\n\n";

// ============================================
// Симуляция навигации пользователя
// ============================================

echo "User Navigation Flow:\n";
echo str_repeat("-", 50) . "\n\n";

// 1. Пользователь заходит на главную
Route::dispatch('/', 'GET');
echo "1. Current: " . Route::currentRouteName() . "\n";
echo "   Previous: " . (Route::previousRouteName() ?? 'none') . "\n\n";

// 2. Переходит к списку товаров
Route::dispatch('/products', 'GET');
echo "2. Current: " . Route::currentRouteName() . "\n";
echo "   Previous: " . Route::previousRouteName() . "\n\n";

// 3. Смотрит конкретный товар
Route::dispatch('/products/123', 'GET');
echo "3. Current: " . Route::currentRouteName() . "\n";
echo "   Previous: " . Route::previousRouteName() . "\n";
echo "   Previous URI: " . Route::router()->previousRouteUri() . "\n\n";

// 4. Добавляет в корзину
Route::dispatch('/cart', 'GET');
echo "4. Current: " . Route::currentRouteName() . "\n";
echo "   Previous: " . Route::previousRouteName() . "\n\n";

// 5. Переходит к оформлению
Route::dispatch('/checkout', 'GET');
echo "5. Current: " . Route::currentRouteName() . "\n";
echo "   Previous: " . Route::previousRouteName() . "\n\n";

// 6. Завершает покупку
Route::dispatch('/thank-you', 'GET');
echo "6. Current: " . Route::currentRouteName() . "\n";
echo "   Previous: " . Route::previousRouteName() . "\n\n";

// ============================================
// Практическое применение
// ============================================

echo "\n" . str_repeat("=", 50) . "\n";
echo "Practical Examples\n";
echo str_repeat("=", 50) . "\n\n";

// Пример 1: Кнопка "Назад"
echo "Example 1: Back Button\n";
echo str_repeat("-", 50) . "\n";

function generateBackButton(): string
{
    $previousRoute = Route::router()->previous();
    
    if ($previousRoute === null) {
        return '<button disabled>No Previous Page</button>';
    }
    
    $uri = $previousRoute->getUri();
    $name = $previousRoute->getName();
    
    return "<a href=\"{$uri}\" class=\"btn-back\">← Back to {$name}</a>";
}

echo generateBackButton() . "\n\n";

// Пример 2: Breadcrumbs (хлебные крошки)
echo "Example 2: Breadcrumbs\n";
echo str_repeat("-", 50) . "\n";

function generateBreadcrumbs(): string
{
    $breadcrumbs = [];
    
    if ($previous = Route::router()->previous()) {
        $breadcrumbs[] = [
            'name' => $previous->getName() ?? 'Previous',
            'uri' => $previous->getUri(),
        ];
    }
    
    if ($current = Route::router()->current()) {
        $breadcrumbs[] = [
            'name' => $current->getName() ?? 'Current',
            'uri' => $current->getUri(),
            'active' => true,
        ];
    }
    
    $html = '<nav class="breadcrumbs">';
    $html .= '<a href="/">Home</a>';
    
    foreach ($breadcrumbs as $crumb) {
        if (isset($crumb['active'])) {
            $html .= " > <span class=\"active\">{$crumb['name']}</span>";
        } else {
            $html .= " > <a href=\"{$crumb['uri']}\">{$crumb['name']}</a>";
        }
    }
    
    $html .= '</nav>';
    
    return $html;
}

echo generateBreadcrumbs() . "\n\n";

// Пример 3: Redirect на предыдущую страницу после действия
echo "Example 3: Redirect After Action\n";
echo str_repeat("-", 50) . "\n";

function handleFormSubmission(): array
{
    // Обработка формы...
    $success = true;
    
    if ($success) {
        $redirectTo = Route::router()->previousRouteUri() ?? '/';
        return [
            'status' => 'success',
            'message' => 'Form submitted successfully',
            'redirect' => $redirectTo,
        ];
    }
    
    return [
        'status' => 'error',
        'message' => 'Form submission failed',
    ];
}

$result = handleFormSubmission();
echo "Redirect to: {$result['redirect']}\n\n";

// Пример 4: Проверка перехода между страницами
echo "Example 4: Navigation Guard\n";
echo str_repeat("-", 50) . "\n";

function canAccessCheckout(): bool
{
    $previous = Route::router()->previousRouteName();
    
    // Можно попасть на checkout только из корзины
    if ($previous !== 'cart') {
        return false;
    }
    
    return true;
}

if (Route::currentRouteNamed('thank-you')) {
    echo "Access to thank-you page: ";
    echo (Route::previousRouteNamed('checkout') ? '✓ Allowed' : '✗ Denied') . "\n";
    echo "Reason: Must come from checkout page\n\n";
}

// Пример 5: Логирование навигации
echo "Example 5: Navigation Logger\n";
echo str_repeat("-", 50) . "\n";

function logNavigation(): void
{
    $current = Route::router()->current();
    $previous = Route::router()->previous();
    
    $log = [
        'timestamp' => date('Y-m-d H:i:s'),
        'from' => $previous?->getName() ?? 'direct',
        'from_uri' => $previous?->getUri() ?? null,
        'to' => $current?->getName() ?? 'unknown',
        'to_uri' => $current?->getUri() ?? null,
        'user_id' => $_SESSION['user_id'] ?? 'guest',
    ];
    
    echo json_encode($log, JSON_PRETTY_PRINT) . "\n";
}

logNavigation();
echo "\n";

// Пример 6: Условная логика на основе истории
echo "Example 6: Conditional Logic\n";
echo str_repeat("-", 50) . "\n";

function showReturnToCartButton(): bool
{
    // Показываем кнопку "Вернуться в корзину" только если пользователь
    // пришел со страницы корзины
    return Route::previousRouteNamed('cart');
}

echo "Show 'Return to Cart' button: ";
echo (showReturnToCartButton() ? 'Yes' : 'No') . "\n\n";

// Пример 7: Analytics
echo "Example 7: User Flow Analytics\n";
echo str_repeat("-", 50) . "\n";

function trackUserFlow(): array
{
    return [
        'conversion_funnel' => [
            'entered_from' => Route::previousRouteName(),
            'current_step' => Route::currentRouteName(),
            'completed' => Route::currentRouteNamed('thank-you'),
        ],
        'abandoned' => Route::previousRouteNamed('checkout') && 
                      !Route::currentRouteNamed('thank-you'),
    ];
}

$analytics = trackUserFlow();
echo "Analytics Data:\n";
echo "  Current Step: {$analytics['conversion_funnel']['current_step']}\n";
echo "  Entered From: {$analytics['conversion_funnel']['entered_from']}\n";
echo "  Completed: " . ($analytics['conversion_funnel']['completed'] ? 'Yes' : 'No') . "\n\n";

// Пример 8: Security Check
echo "Example 8: Security - CSRF Referrer Check\n";
echo str_repeat("-", 50) . "\n";

function validateReferrer(string $expectedRoute): bool
{
    $previous = Route::router()->previousRouteName();
    
    if ($previous !== $expectedRoute) {
        error_log("Security: Invalid referrer. Expected {$expectedRoute}, got {$previous}");
        return false;
    }
    
    return true;
}

// Для thank-you страницы требуется прийти с checkout
$isValid = validateReferrer('checkout');
echo "Referrer validation: " . ($isValid ? '✓ Valid' : '✗ Invalid') . "\n";
echo "Expected: checkout\n";
echo "Actual: " . Route::previousRouteName() . "\n\n";

echo "✓ Navigation examples completed!\n";

