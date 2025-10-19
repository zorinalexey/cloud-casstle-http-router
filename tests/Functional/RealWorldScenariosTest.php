<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Functional;

use CloudCastle\Http\Router\Exceptions\RouteNotFoundException;
use CloudCastle\Http\Router\Facade\Route;
use CloudCastle\Http\Router\Router;
use PHPUnit\Framework\TestCase;

/**
 * Functional tests simulating real-world usage scenarios.
 */
class RealWorldScenariosTest extends TestCase
{
    public function testEcommerceApplication(): void
    {
        // Public routes
        Route::get('/', fn (): string => 'home')->name('home');
        Route::get('/products', fn (): string => 'products')->name('products.index');
        Route::get('/products/{id:\d+}', fn ($id): string => 'product ' . $id)->name('products.show');

        // Shopping cart
        Route::get('/cart', fn (): string => 'cart')->name('cart');
        Route::post('/cart/add/{productId}', fn ($id): string => 'added')->name('cart.add');

        // Checkout (requires coming from cart)
        Route::post('/checkout', fn (): string => 'checkout')->name('checkout')
            ->middleware('cart_required');

        // Admin panel
        Route::group([
            'prefix' => '/admin',
            'middleware' => ['auth', 'admin'],
            'whitelistIp' => ['192.168.1.1'],
        ], function (): void {
            Route::get('/dashboard', fn (): string => 'admin')->name('admin.dashboard');
            Route::get('/products', fn (): string => 'admin products')->name('admin.products');
            Route::post('/products', fn (): string => 'create product')->name('admin.products.store');
        });

        // Test user flow (указываем domain)
        Route::dispatch('/', 'GET', 'shop.example.com');
        $this->assertEquals('home', Route::currentRouteName());

        Route::dispatch('/products', 'GET', 'shop.example.com');
        $this->assertEquals('products.index', Route::currentRouteName());
        $this->assertEquals('home', Route::previousRouteName());

        Route::dispatch('/products/123', 'GET', 'shop.example.com');
        $this->assertEquals(['id' => '123'], Route::current()?->getParameters());
    }

    public function testApiGateway(): void
    {
        // API v1
        Route::group([
            'prefix' => '/api/v1',
            'throttle' => 60,
            'middleware' => 'api_v1',
        ], function (): void {
            Route::get('/users', fn (): string => 'users')->tag(['api', 'v1', 'public']);
            Route::get('/posts', fn (): string => 'posts')->tag(['api', 'v1', 'public']);
        });

        // API v2 (higher rate limit for paying customers)
        Route::group([
            'prefix' => '/api/v2',
            'throttle' => ['max' => 1000, 'decay' => 1],
            'middleware' => ['api_v2', 'auth'],
        ], function (): void {
            Route::get('/users', fn (): string => 'users v2')->tag(['api', 'v2']);
            Route::get('/analytics', fn (): string => 'analytics')->tag(['api', 'v2', 'premium']);
        });

        // Test API versions
        $v1Routes = Route::getRoutesByTag('v1');
        $v2Routes = Route::getRoutesByTag('v2');

        $this->assertCount(2, $v1Routes);
        $this->assertCount(2, $v2Routes);

        // Test different rate limits
        $v1Route = Route::dispatch('api/v1/users', 'GET');
        $v2Route = Route::dispatch('api/v2/users', 'GET');

        $this->assertEquals(60, $v1Route->getRateLimiter()?->getMaxAttempts());
        $this->assertEquals(1000, $v2Route->getRateLimiter()?->getMaxAttempts());
    }

    public function testMultiTenantApplication(): void
    {
        // Tenant 1
        Route::group(['domain' => 'tenant1.app.com'], function (): void {
            Route::get('/dashboard', fn (): string => 'tenant1 dashboard')->name('tenant1.dashboard');
            Route::get('/settings', fn (): string => 'tenant1 settings')->name('tenant1.settings');
        });

        // Tenant 2
        Route::group(['domain' => 'tenant2.app.com'], function (): void {
            Route::get('/dashboard', fn (): string => 'tenant2 dashboard')->name('tenant2.dashboard');
            Route::get('/settings', fn (): string => 'tenant2 settings')->name('tenant2.settings');
        });

        // Test tenant isolation
        $tenant1Route = Route::dispatch('/dashboard', 'GET', 'tenant1.app.com');
        $this->assertEquals('tenant1.dashboard', $tenant1Route->getName());

        $tenant2Route = Route::dispatch('/dashboard', 'GET', 'tenant2.app.com');
        $this->assertEquals('tenant2.dashboard', $tenant2Route->getName());

        $this->assertNotEquals($tenant1Route->getName(), $tenant2Route->getName());
    }

    public function testMicroservicesArchitecture(): void
    {
        // User Service (port 8081)
        Route::group(['prefix' => '/users', 'port' => 8081], function (): void {
            Route::get('/', fn (): string => 'users')->tag('user-service');
            Route::get('/{id}', fn ($id): string => 'user ' . $id)->tag('user-service');
        });

        // Product Service (port 8082)
        Route::group(['prefix' => '/products', 'port' => 8082], function (): void {
            Route::get('/', fn (): string => 'products')->tag('product-service');
            Route::get('/{id}', fn ($id): string => 'product ' . $id)->tag('product-service');
        });

        // Order Service (port 8083)
        Route::group(['prefix' => '/orders', 'port' => 8083], function (): void {
            Route::post('/', fn (): string => 'create order')->tag('order-service');
        });

        // Test service isolation by port
        $userRoute = Route::dispatch('users/', 'GET', null, null, 8081);
        $this->assertContains('user-service', $userRoute->getTags());

        $productRoute = Route::dispatch('products/', 'GET', null, null, 8082);
        $this->assertContains('product-service', $productRoute->getTags());

        // Verify port isolation - try to access user service on product service port
        $this->expectException(RouteNotFoundException::class);
        Route::dispatch('users/', 'GET', null, null, 8082); // Wrong port - user service requires 8081
    }

    public function testContentManagementSystem(): void
    {
        // Public pages
        Route::get('/{page}', fn ($page): string => 'page: ' . $page)
            ->name('page.show')
            ->tag('public');

        // Blog
        Route::group(['prefix' => '/blog'], function (): void {
            Route::get('/', fn (): string => 'blog index')->name('blog.index');
            Route::get('/{slug}', fn ($slug): string => 'post: ' . $slug)->name('blog.show');
            Route::get('/category/{category}', fn ($cat): string => 'category: ' . $cat)->name('blog.category');
        });

        // Admin CMS
        Route::group([
            'prefix' => '/admin',
            'middleware' => ['auth', 'can:edit-content'],
            'whitelistIp' => ['192.168.1.100'],
        ], function (): void {
            Route::get('/pages', fn (): string => 'manage pages')->name('admin.pages');
            Route::get('/posts', fn (): string => 'manage posts')->name('admin.posts');
            Route::post('/posts', fn (): string => 'create post')->name('admin.posts.store');
        });

        // Test public access
        $route = Route::dispatch('/about-us', 'GET');
        $this->assertArrayHasKey('page', $route->getParameters());

        // Test blog
        $blogRoute = Route::dispatch('blog/my-first-post', 'GET');
        $this->assertEquals('blog.show', $blogRoute->getName());
        $this->assertArrayHasKey('slug', $blogRoute->getParameters());
    }

    public function testSaaSPlatform(): void
    {
        // Free tier with strict rate limiting
        Route::group([
            'prefix' => '/api/free',
            'throttle' => ['max' => 10, 'decay' => 1, 'key' => 'free-tier'],
            'tags' => ['api', 'free'],
        ], function (): void {
            Route::get('/data', fn (): string => 'data')->name('free.data');
        });

        // Pro tier with higher limits
        Route::group([
            'prefix' => '/api/pro',
            'throttle' => ['max' => 100, 'decay' => 1, 'key' => 'pro-tier'],
            'middleware' => 'verify_subscription',
            'tags' => ['api', 'pro'],
        ], function (): void {
            Route::get('/data', fn (): string => 'pro data')->name('pro.data');
            Route::get('/analytics', fn (): string => 'analytics')->name('pro.analytics');
        });

        // Enterprise tier with no limits
        Route::group([
            'prefix' => '/api/enterprise',
            'middleware' => ['verify_enterprise', 'api'],
            'tags' => ['api', 'enterprise'],
        ], function (): void {
            Route::get('/data', fn (): string => 'enterprise data');
            Route::get('/analytics', fn (): string => 'advanced analytics');
            Route::get('/custom', fn (): string => 'custom features');
        });

        // Test tier isolation - check routes exist
        $allRoutes = Route::getRoutes();
        $this->assertGreaterThanOrEqual(6, count($allRoutes)); // 1 + 2 + 3

        // Test rate limits (prefix 'api/free' + '/data' = 'api/free/data' без /)
        $freeRoute = Route::dispatch('api/free/data', 'GET');
        $this->assertNotNull($freeRoute);
        $this->assertNotNull($freeRoute->getRateLimiter());

        $proRoute = Route::dispatch('api/pro/data', 'GET');
        $this->assertNotNull($proRoute);
        $this->assertNotNull($proRoute->getRateLimiter());
    }

    public function testRouteIntrospection(): void
    {
        // Create complex routing structure
        Route::get('/simple', fn (): string => '')->tag('simple');

        Route::group(['prefix' => '/api', 'tags' => ['api']], function (): void {
            Route::get('/users', fn (): string => '')->middleware('auth');
            Route::get('/public', fn (): string => '')->tag('public');
        });

        // Introspection
        $stats = Route::router()->getRouteStats();

        $this->assertGreaterThan(0, $stats['total']);
        $this->assertArrayHasKey('by_method', $stats);
        $this->assertGreaterThan(0, $stats['by_method']['GET']);

        // Check tags (tags из группы применяются)
        $this->assertTrue(Route::router()->hasTag('simple'));

        $allTags = Route::router()->getAllTags();
        $this->assertContains('simple', $allTags);
        $this->assertGreaterThan(0, count($allTags));
    }

    protected function setUp(): void
    {
        Router::reset();
    }
}
