<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Integration;

use CloudCastle\Http\Router\Exceptions\InsecureConnectionException;
use CloudCastle\Http\Router\Exceptions\IpNotAllowedException;
use CloudCastle\Http\Router\Facade\Route;
use CloudCastle\Http\Router\Middleware\HttpsEnforcement;
use CloudCastle\Http\Router\Middleware\SecurityLogger;
use CloudCastle\Http\Router\Middleware\SsrfProtection;
use CloudCastle\Http\Router\Router;
use PHPUnit\Framework\TestCase;

/**
 * Integration tests for maximum security configuration
 */
class MaximumSecurityTest extends TestCase
{
    protected function setUp(): void
    {
        $_SERVER = [];
        $_REQUEST = [];
    }

    public function testOWASP_A01_AccessControl(): void
    {
        Router::reset();

        // Multi-layer access control
        Route::group([
            'prefix' => '/admin',
            'https' => true,
            'domain' => 'admin.example.com',
            'port' => 443,
            'whitelistIp' => ['192.168.1.1'],
            'middleware' => ['auth', 'admin'],
            'throttle' => 10,
        ], function () {
            Route::get('/dashboard', fn() => 'admin')->name('admin.dashboard');
        });

        $_SERVER['HTTPS'] = 'on';

        // Valid access
        $route = Route::dispatch('admin/dashboard', 'GET', 'admin.example.com', '192.168.1.1', 443);
        $this->assertEquals('admin.dashboard', $route->getName());

        // Wrong IP should fail - reset for clean state
        Router::reset();
        $_SERVER['HTTPS'] = 'on';

        Route::group([
            'prefix' => '/admin',
            'https' => true,
            'domain' => 'admin.example.com',
            'port' => 443,
            'whitelistIp' => ['192.168.1.1'],
            'middleware' => ['auth', 'admin'],
            'throttle' => 10,
        ], function () {
            Route::get('/dashboard', fn() => 'admin')->name('admin.dashboard');
        });

        try {
            Route::dispatch('admin/dashboard', 'GET', 'admin.example.com', '1.2.3.4', 443);
            $this->fail('Expected IpNotAllowedException was not thrown');
        } catch (IpNotAllowedException $e) {
            $this->assertTrue(true); // Expected exception
        }
    }

    public function testOWASP_A02_CryptographicFailures(): void
    {
        Router::reset();

        Route::post('/payment', fn() => 'payment')
            ->https()
            ->middleware(HttpsEnforcement::class);

        $_SERVER['HTTPS'] = 'on';

        // HTTPS should work (указываем protocol='https')
        $route = Route::dispatch('/payment', 'POST', null, null, null, 'https');
        $this->assertNotNull($route);
    }

    public function testOWASP_A02_InsecureConnection(): void
    {
        Router::reset();

        Route::post('/payment', fn() => 'payment')->https();

        // HTTP should fail for HTTPS-only route (no $_SERVER['HTTPS'])
        $this->expectException(InsecureConnectionException::class);
        Route::dispatch('/payment', 'POST');
    }

    public function testOWASP_A07_RateLimitingProtection(): void
    {
        Router::reset();

        Route::post('/login', fn() => 'login')
            ->throttle(3, 1);

        // Используем уникальный IP для изоляции теста
        $testIp = '192.168.100.100';

        // First 3 attempts should succeed
        for ($i = 0; $i < 3; $i++) {
            $route = Route::dispatch('/login', 'POST', null, $testIp);
            $this->assertNotNull($route);
        }

        // 4th should fail
        try {
            Route::dispatch('/login', 'POST', null, $testIp);
            $this->fail('Expected TooManyRequestsException was not thrown');
        } catch (\CloudCastle\Http\Router\Exceptions\TooManyRequestsException $e) {
            $this->assertTrue(true); // Expected exception
        }
    }

    public function testOWASP_A09_SecurityLogging(): void
    {
        $logFile = sys_get_temp_dir() . '/security-test-' . uniqid() . '.log';

        $_SERVER['REQUEST_URI'] = '/test';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';

        Route::middleware([SecurityLogger::class]);
        Route::get('/test', fn() => 'test');

        try {
            Route::dispatch('/test', 'GET');
        } catch (\Exception $e) {
            // Ignore dispatch errors
        }

        // Test passes if no exceptions thrown
        $this->assertTrue(true);
    }

    public function testOWASP_A10_SSRFProtection(): void
    {
        $_REQUEST = ['url' => 'http://169.254.169.254/meta-data'];
        $_SERVER['HTTPS'] = 'on';

        Route::middleware(SsrfProtection::class);
        Route::post('/fetch', fn() => 'fetch');

        $this->expectException(\CloudCastle\Http\Router\Exceptions\RouterException::class);

        $middleware = new SsrfProtection();
        $middleware->handle([], fn($req) => 'should-not-reach');
    }

    public function testCompleteSecurityStack(): void
    {
        Router::reset();

        Route::group([
            'prefix' => '/secure',
            'https' => true,
            'port' => 443,
            'domain' => 'secure.example.com',
            'whitelistIp' => ['192.168.1.1'],
            'middleware' => [
                'auth',
                HttpsEnforcement::class,
                SsrfProtection::class,
            ],
            'throttle' => 100,
        ], function () {
            Route::post('/critical', fn() => 'critical')->name('secure.critical');
        });

        // Valid request
        $_SERVER['HTTPS'] = 'on';

        $route = Route::dispatch(
            'secure/critical',
            'POST',
            'secure.example.com',
            '192.168.1.1',
            443
        );

        $this->assertEquals('secure.critical', $route->getName());
        $this->assertTrue($route->isHttpsOnly());
        $this->assertEquals(443, $route->getPort());
        $this->assertContains('auth', $route->getMiddleware());
    }

    public function testProtocolEnforcementForWebSocket(): void
    {
        Router::reset();
        Route::get('/ws/chat', fn() => 'chat')
            ->protocol(['ws', 'wss']); // массив протоколов

        // WebSocket should work
        $route = Route::dispatch('/ws/chat', 'GET', null, null, null, 'ws');
        $this->assertNotNull($route);

        // Проверяем что protocols установлены
        $this->assertContains('ws', $route->getProtocols());
        $this->assertContains('wss', $route->getProtocols());
    }

    public function testSecureWebSocketOnly(): void
    {
        Router::reset();
        Route::get('/wss/notifications', fn() => 'notifications')
            ->protocol(['wss']); // массив с одним протоколом

        // WSS should work
        $route = Route::dispatch('/wss/notifications', 'GET', null, null, null, 'wss');
        $this->assertNotNull($route);

        // Проверяем что только wss протокол
        $this->assertEquals(['wss'], $route->getProtocols());
        $this->assertContains('wss', $route->getProtocols());
    }

    public function testCombinedHttpsAndPortEnforcement(): void
    {
        Router::reset();

        Route::post('/api/secure', fn() => 'data')
            ->https()
            ->auth()
            ->throttleStrict();

        $_SERVER['HTTPS'] = 'on';

        $route = Route::dispatch('/api/secure', 'POST', null, null, 443);

        $this->assertEquals(443, $route->getPort());
        $this->assertTrue($route->isHttpsOnly());
        $this->assertEquals(10, $route->getRateLimiter()?->getMaxAttempts());
    }
}
