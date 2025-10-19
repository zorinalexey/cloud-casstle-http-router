<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Middleware;

use Closure;
use CloudCastle\Http\Router\Contracts\MiddlewareInterface;
use RuntimeException;

/**
 * Authentication middleware.
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 */
class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @param array<string> $allowedRoles
     */
    public function __construct(
        private readonly ?Closure $authenticator = null,
        private readonly array $allowedRoles = []
    ) {
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function handle(mixed $uri, callable $next, array $parameters = []): mixed
    {
        $user = $this->authenticate();

        if ($user === null) {
            throw new RuntimeException('Unauthorized', 401);
        }

        // Check roles if specified
        if ($this->allowedRoles !== [] && !$this->hasRole($user)) {
            throw new RuntimeException('Forbidden', 403);
        }

        // Pass user to next middleware
        $parameters['user'] = $user;

        return $next($uri);
    }

    /**
     * Authenticate user.
     *
     * @return array<string, mixed>|null
     */
    private function authenticate(): ?array
    {
        // Use custom authenticator if provided
        if ($this->authenticator instanceof \Closure) {
            return ($this->authenticator)();
        }

        // Default: check for Bearer token
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

        if (str_starts_with((string) $authHeader, 'Bearer ')) {
            $token = substr((string) $authHeader, 7);

            // Here you would validate the token
            // For demo purposes, just return a user
            return ['id' => 1, 'name' => 'User', 'token' => $token, 'roles' => ['user']];
        }

        // Check session
        if (isset($_SESSION['user_id'])) {
            return ['id' => $_SESSION['user_id'], 'roles' => $_SESSION['user_roles'] ?? ['user']];
        }

        return null;
    }

    /**
     * Check if user has required role.
     *
     * @param array<string, mixed> $user
     */
    private function hasRole(array $user): bool
    {
        $userRoles = $user['roles'] ?? [];

        if (!is_array($userRoles)) {
            return false;
        }

        foreach ($this->allowedRoles as $role) {
            if (in_array($role, $userRoles, true)) {
                return true;
            }
        }

        return false;
    }
}
