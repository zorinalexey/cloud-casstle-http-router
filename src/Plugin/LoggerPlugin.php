<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Plugin;

use CloudCastle\Http\Router\Route;

/**
 * Logger plugin for logging router events.
 */
class LoggerPlugin extends AbstractPlugin
{
    private string $logFile;

    private bool $logRouteRegistration = true;

    private bool $logDispatches = true;

    private bool $logExceptions = true;

    /**
     * @param string $logFile Path to log file
     */
    public function __construct(string $logFile = '/tmp/router.log')
    {
        $this->logFile = $logFile;
    }

    /**
     * Get plugin name.
     */
    public function getName(): string
    {
        return 'logger';
    }

    /**
     * Get plugin version.
     */
    public function getVersion(): string
    {
        return '1.0.0';
    }

    /**
     * Called when route is registered.
     */
    public function onRouteRegistered(Route $route): void
    {
        if (!$this->logRouteRegistration) {
            return;
        }

        $this->log(sprintf(
            '[ROUTE REGISTERED] %s %s -> %s',
            implode('|', $route->getMethods()),
            $route->getUri(),
            $route->getName() ?? 'unnamed'
        ));
    }

    /**
     * Called before route dispatch.
     */
    public function beforeDispatch(Route $route, string $uri, string $method): void
    {
        if (!$this->logDispatches) {
            return;
        }

        $this->log(sprintf(
            '[BEFORE DISPATCH] %s %s -> Route: %s',
            $method,
            $uri,
            $route->getName() ?? $route->getUri()
        ));
    }

    /**
     * Called after route dispatch.
     */
    public function afterDispatch(Route $route, mixed $result): mixed
    {
        if ($this->logDispatches) {
            $resultType = get_debug_type($result);
            $this->log(sprintf(
                '[AFTER DISPATCH] Route: %s, Result Type: %s',
                $route->getName() ?? $route->getUri(),
                $resultType
            ));
        }

        return $result; // Don't modify result
    }

    /**
     * Called when exception occurs.
     */
    public function onException(\Exception $exception): void
    {
        if (!$this->logExceptions) {
            return;
        }

        $this->log(sprintf(
            '[EXCEPTION] %s: %s in %s:%d',
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        ));
    }

    /**
     * Set log file path.
     */
    public function setLogFile(string $logFile): self
    {
        $this->logFile = $logFile;

        return $this;
    }

    /**
     * Enable/disable route registration logging.
     */
    public function setLogRouteRegistration(bool $enabled): self
    {
        $this->logRouteRegistration = $enabled;

        return $this;
    }

    /**
     * Enable/disable dispatch logging.
     */
    public function setLogDispatches(bool $enabled): self
    {
        $this->logDispatches = $enabled;

        return $this;
    }

    /**
     * Enable/disable exception logging.
     */
    public function setLogExceptions(bool $enabled): self
    {
        $this->logExceptions = $enabled;

        return $this;
    }

    /**
     * Write log entry.
     */
    private function log(string $message): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $entry = "[{$timestamp}] {$message}\n";

        file_put_contents($this->logFile, $entry, FILE_APPEND | LOCK_EX);
    }
}

