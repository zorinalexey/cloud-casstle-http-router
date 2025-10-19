<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Middleware;

use CloudCastle\Http\Router\Contracts\MiddlewareInterface;
use CloudCastle\Http\Router\Facade\Route;
use Throwable;

/**
 * Built-in security logging middleware.
 */
class SecurityLogger implements MiddlewareInterface
{
    public const LEVEL_INFO = 1;

    public const LEVEL_WARNING = 2;

    public const LEVEL_ERROR = 3;

    private readonly string $logFile;

    public function __construct(?string $logFile = null, private readonly int $logLevel = self::LEVEL_INFO)
    {
        $this->logFile = $logFile ?? sys_get_temp_dir() . '/router-security.log';
    }

    /**
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function handle(mixed $request, callable $next): mixed
    {
        $startTime = microtime(true);
        $route = Route::current();

        // Log request
        $this->log(self::LEVEL_INFO, 'Request started', [
            'uri' => $_SERVER['REQUEST_URI'] ?? '',
            'method' => $_SERVER['REQUEST_METHOD'] ?? '',
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'route' => $route?->getName(),
            'timestamp' => date('Y-m-d H:i:s'),
        ]);

        try {
            $response = $next($request);

            // Log successful response
            $duration = microtime(true) - $startTime;
            $this->log(self::LEVEL_INFO, 'Request completed', [
                'route' => $route?->getName(),
                'duration' => number_format($duration * 1000, 2) . 'ms',
                'status' => 'success',
            ]);

            return $response;
        } catch (Throwable $throwable) {
            // Log error
            $this->log(self::LEVEL_ERROR, 'Request failed', [
                'route' => $route?->getName(),
                'error' => $throwable->getMessage(),
                'exception' => $throwable::class,
                'trace' => $throwable->getTraceAsString(),
            ]);

            throw $throwable;
        }
    }

    /**
     * Log message.
     *
     * @param array<string, mixed> $context
     */
    private function log(int $level, string $message, array $context = []): void
    {
        if ($level < $this->logLevel) {
            return;
        }

        $levelName = match ($level) {
            self::LEVEL_INFO => 'INFO',
            self::LEVEL_WARNING => 'WARNING',
            self::LEVEL_ERROR => 'ERROR',
            default => 'UNKNOWN',
        };

        $timestamp = date('Y-m-d H:i:s');
        $contextJson = json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $logLine = sprintf('[%s] %s: %s %s%s', $timestamp, $levelName, $message, $contextJson, PHP_EOL);

        file_put_contents($this->logFile, $logLine, FILE_APPEND | LOCK_EX);
    }
}
