<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\Middleware\AuthMiddleware;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class AuthMiddlewareTest extends TestCase
{
    public function testAuthenticateWithBearerToken(): void
    {
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer test-token-123';

        $middleware = new AuthMiddleware();

        $called = false;
        $middleware->handle('/test', function () use (&$called): string {
            $called = true;

            return 'response';
        });

        $this->assertTrue($called);
    }

    public function testAuthenticateWithSession(): void
    {
        $_SESSION['user_id'] = 123;
        $_SESSION['user_roles'] = ['user', 'editor'];

        $middleware = new AuthMiddleware();

        $called = false;
        $middleware->handle('/test', function () use (&$called): string {
            $called = true;

            return 'response';
        });

        $this->assertTrue($called);
    }

    public function testUnauthenticatedRequest(): void
    {
        $middleware = new AuthMiddleware();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unauthorized');

        $middleware->handle('/test', fn (): string => 'should not reach here');
    }

    public function testCustomAuthenticator(): void
    {
        $customAuth = new AuthMiddleware(
            authenticator : fn (): array => ['id' => 1, 'name' => 'Test User', 'roles' => ['user']]
        );

        $called = false;
        $customAuth->handle('/test', function () use (&$called): string {
            $called = true;

            return 'response';
        });

        $this->assertTrue($called);
    }

    public function testCustomAuthenticatorReturnsNull(): void
    {
        $customAuth = new AuthMiddleware(
            authenticator : fn (): ?array => null
        );

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unauthorized');

        $customAuth->handle('/test', fn (): string => 'should not reach here');
    }

    public function testRoleBasedAccess(): void
    {
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer test-token';

        $middleware = new AuthMiddleware(
            allowedRoles : ['user']
        );

        $called = false;
        $middleware->handle('/test', function () use (&$called): string {
            $called = true;

            return 'response';
        });

        $this->assertTrue($called);
    }

    public function testInsufficientRoles(): void
    {
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer test-token';

        $middleware = new AuthMiddleware(
            allowedRoles : ['admin', 'super-admin']
        );

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Forbidden');

        $middleware->handle('/test', fn (): string => 'should not reach here');
    }

    public function testMultipleAllowedRoles(): void
    {
        $_SESSION['user_id'] = 1;
        $_SESSION['user_roles'] = ['editor', 'moderator'];

        $middleware = new AuthMiddleware(
            allowedRoles : ['admin', 'editor', 'moderator']
        );

        $called = false;
        $middleware->handle('/test', function () use (&$called): string {
            $called = true;

            return 'response';
        });

        $this->assertTrue($called);
    }

    public function testNoRolesRequired(): void
    {
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer test-token';

        $middleware = new AuthMiddleware(
            allowedRoles : []
        );

        $called = false;
        $middleware->handle('/test', function () use (&$called): string {
            $called = true;

            return 'response';
        });

        $this->assertTrue($called);
    }

    public function testUserWithoutRoles(): void
    {
        $customAuth = new AuthMiddleware(
            authenticator : fn (): array => ['id' => 1, 'name' => 'User'],
            allowedRoles : ['admin']
        );

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Forbidden');

        $customAuth->handle('/test', fn (): string => 'should not reach here');
    }

    protected function setUp(): void
    {
        // Clean up session and server vars
        $_SESSION = [];
        unset($_SERVER['HTTP_AUTHORIZATION']);
    }
}
