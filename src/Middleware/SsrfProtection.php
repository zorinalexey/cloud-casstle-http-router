<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Middleware;

use CloudCastle\Http\Router\Contracts\MiddlewareInterface;
use CloudCastle\Http\Router\Exceptions\RouterException;

/**
 * SSRF (Server-Side Request Forgery) Protection Middleware.
 */
class SsrfProtection implements MiddlewareInterface
{
    /** @var array<string> Allowed URL schemes */
    private array $allowedSchemes = ['http', 'https'];

    /** @var array<string> Blocked hosts */
    private array $blockedHosts = [
        'localhost',
        '127.0.0.1',
        '0.0.0.0',
        '::1',
        '169.254.169.254', // AWS metadata
        'metadata.google.internal', // GCP metadata
    ];

    /** @var array<string> Allowed domains whitelist */
    private array $allowedDomains = [];

    /**
     * @param array<string>|null $allowedDomains
     * @param array<string>|null $blockedHosts
     */
    public function __construct(?array $allowedDomains = null, ?array $blockedHosts = null)
    {
        if ($allowedDomains !== null) {
            $this->allowedDomains = $allowedDomains;
        }

        if ($blockedHosts !== null) {
            $this->blockedHosts = array_merge($this->blockedHosts, $blockedHosts);
        }
    }

    /**
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function handle(mixed $request, callable $next): mixed
    {
        // Check all request parameters for potential SSRF
        foreach ($_REQUEST as $value) {
            if ($this->looksLikeUrl($value)) {
                $this->validateUrl($value);
            }
        }

        return $next($request);
    }

    /**
     * Check if string looks like a URL.
     */
    private function looksLikeUrl(mixed $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        return preg_match('#^(https?|ftp|file)://#i', $value) === 1;
    }

    /**
     * Validate URL for SSRF vulnerabilities.
     *
     * @throws RouterException
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private function validateUrl(string $url): void
    {
        $parsed = parse_url($url);

        if ($parsed === false) {
            throw new RouterException('Invalid URL format');
        }

        // Check scheme
        $scheme = strtolower($parsed['scheme'] ?? '');
        if (!in_array($scheme, $this->allowedSchemes)) {
            throw new RouterException(sprintf("URL scheme '%s' not allowed", $scheme));
        }

        // Check host
        $host = strtolower($parsed['host'] ?? '');

        // Block private/internal hosts
        if (in_array($host, $this->blockedHosts)) {
            throw new RouterException(sprintf("Access to '%s' is blocked for security reasons", $host));
        }

        // Check for internal IP ranges
        if ($this->isPrivateIp($host)) {
            throw new RouterException('Access to private IP addresses is blocked');
        }

        // If whitelist is set, check domain
        if ($this->allowedDomains !== []) {
            $allowed = false;
            foreach ($this->allowedDomains as $allowedDomain) {
                if ($host === $allowedDomain || str_ends_with($host, '.' . $allowedDomain)) {
                    $allowed = true;
                    break;
                }
            }

            if (!$allowed) {
                throw new RouterException(sprintf("Domain '%s' is not in allowed list", $host));
            }
        }
    }

    /**
     * Check if host is a private IP.
     */
    private function isPrivateIp(string $host): bool
    {
        // Check if it's an IP address
        if (!filter_var($host, FILTER_VALIDATE_IP)) {
            return false;
        }

        // Check for private/reserved ranges
        return !filter_var(
            $host,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        );
    }
}
