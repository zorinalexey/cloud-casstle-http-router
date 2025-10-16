<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\Exceptions\InsecureConnectionException;
use CloudCastle\Http\Router\Exceptions\RouterException;
use CloudCastle\Http\Router\Middleware\HttpsEnforcement;
use CloudCastle\Http\Router\Middleware\SecurityLogger;
use CloudCastle\Http\Router\Middleware\SsrfProtection;
use PHPUnit\Framework\TestCase;

class SecurityMiddlewareTest extends TestCase
{
    protected function setUp(): void
    {
        // Clean up any previous server vars
        $_SERVER = [];
        $_REQUEST = [];
    }

    // ==================== HTTPS Enforcement Tests ====================

    public function testHttpsEnforcementWithHttps(): void
    {
        $_SERVER['HTTPS'] = 'on';
        
        $middleware = new HttpsEnforcement();
        $result = $middleware->handle([], fn($req) => 'success');

        $this->assertEquals('success', $result);
    }

    public function testHttpsEnforcementWithHttp(): void
    {
        $_SERVER['HTTPS'] = 'off';
        
        $this->expectException(InsecureConnectionException::class);
        
        $middleware = new HttpsEnforcement();
        $middleware->handle([], fn($req) => 'should-not-reach');
    }

    public function testHttpsEnforcementWithForwardedProto(): void
    {
        $_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
        
        $middleware = new HttpsEnforcement();
        $result = $middleware->handle([], fn($req) => 'success');

        $this->assertEquals('success', $result);
    }

    public function testHttpsEnforcementWithForwardedSsl(): void
    {
        $_SERVER['HTTP_X_FORWARDED_SSL'] = 'on';
        
        $middleware = new HttpsEnforcement();
        $result = $middleware->handle([], fn($req) => 'success');

        $this->assertEquals('success', $result);
    }

    // ==================== Security Logger Tests ====================

    public function testSecurityLoggerCreation(): void
    {
        $logFile = sys_get_temp_dir() . '/test-router-' . uniqid() . '.log';
        $logger = new SecurityLogger($logFile);

        $this->assertInstanceOf(SecurityLogger::class, $logger);

        if (file_exists($logFile)) {
            unlink($logFile);
        }
    }

    public function testSecurityLoggerLogsRequest(): void
    {
        $logFile = sys_get_temp_dir() . '/test-router-' . uniqid() . '.log';
        $_SERVER['REQUEST_URI'] = '/test';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['HTTP_USER_AGENT'] = 'Test Agent';

        $logger = new SecurityLogger($logFile, SecurityLogger::LEVEL_INFO);
        
        $result = $logger->handle([], fn($req) => 'success');

        $this->assertEquals('success', $result);
        $this->assertFileExists($logFile);

        $logContent = file_get_contents($logFile);
        $this->assertStringContainsString('Request started', $logContent);
        $this->assertStringContainsString('/test', $logContent);

        unlink($logFile);
    }

    public function testSecurityLoggerLogsException(): void
    {
        $logFile = sys_get_temp_dir() . '/test-router-' . uniqid() . '.log';
        $_SERVER['REQUEST_URI'] = '/error';

        $logger = new SecurityLogger($logFile, SecurityLogger::LEVEL_ERROR);

        try {
            $logger->handle([], function() {
                throw new \Exception('Test error');
            });
        } catch (\Exception $e) {
            // Expected
        }

        $this->assertFileExists($logFile);
        $logContent = file_get_contents($logFile);
        $this->assertStringContainsString('Request failed', $logContent);
        $this->assertStringContainsString('Test error', $logContent);

        unlink($logFile);
    }

    // ==================== SSRF Protection Tests ====================

    public function testSsrfProtectionAllowsNormalRequests(): void
    {
        $_REQUEST = ['name' => 'John', 'age' => '25'];
        
        $middleware = new SsrfProtection();
        $result = $middleware->handle([], fn($req) => 'success');

        $this->assertEquals('success', $result);
    }

    public function testSsrfProtectionBlocksLocalhostUrl(): void
    {
        $_REQUEST = ['url' => 'http://localhost/admin'];
        
        $this->expectException(RouterException::class);
        $this->expectExceptionMessageMatches('/localhost.*blocked/i');

        $middleware = new SsrfProtection();
        $middleware->handle([], fn($req) => 'should-not-reach');
    }

    public function testSsrfProtectionBlocksPrivateIp(): void
    {
        $_REQUEST = ['url' => 'http://192.168.1.1/data'];
        
        $this->expectException(RouterException::class);
        $this->expectExceptionMessageMatches('/private.*IP/i');

        $middleware = new SsrfProtection();
        $middleware->handle([], fn($req) => 'should-not-reach');
    }

    public function testSsrfProtectionBlocksMetadataEndpoint(): void
    {
        $_REQUEST = ['url' => 'http://169.254.169.254/latest/meta-data'];
        
        $this->expectException(RouterException::class);

        $middleware = new SsrfProtection();
        $middleware->handle([], fn($req) => 'should-not-reach');
    }

    public function testSsrfProtectionWithWhitelist(): void
    {
        $_REQUEST = ['url' => 'https://api.github.com/users'];
        
        $middleware = new SsrfProtection(['github.com']);
        $result = $middleware->handle([], fn($req) => 'success');

        $this->assertEquals('success', $result);
    }

    public function testSsrfProtectionRejectsNonWhitelistedDomain(): void
    {
        $_REQUEST = ['url' => 'https://evil.com/data'];
        
        $this->expectException(RouterException::class);
        $this->expectExceptionMessageMatches('/not in allowed list/i');

        $middleware = new SsrfProtection(['github.com']);
        $middleware->handle([], fn($req) => 'should-not-reach');
    }

    public function testSsrfProtectionBlocksFileProtocol(): void
    {
        $_REQUEST = ['url' => 'file:///etc/passwd'];
        
        $this->expectException(RouterException::class);
        $this->expectExceptionMessageMatches('/scheme.*not allowed/i');

        $middleware = new SsrfProtection();
        $middleware->handle([], fn($req) => 'should-not-reach');
    }

    public function testSsrfProtectionAllowsSubdomains(): void
    {
        $_REQUEST = ['url' => 'https://api.github.com/users'];
        
        $middleware = new SsrfProtection(['github.com']);
        $result = $middleware->handle([], fn($req) => 'success');

        $this->assertEquals('success', $result);
    }
}

