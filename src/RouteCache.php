<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router;

use RuntimeException;

/**
 * Route cache manager.
 */
class RouteCache
{
    private string $cacheDir;

    private string $cacheFile;

    private bool $cacheEnabled = true;

    public function __construct(?string $cacheDir = null)
    {
        $this->cacheDir = $cacheDir ?? sys_get_temp_dir() . '/router-cache';
        $this->cacheFile = $this->cacheDir . '/routes.cache.php';
    }

    /**
     * Enable or disable caching.
     */
    public function setEnabled(bool $enabled): self
    {
        $this->cacheEnabled = $enabled;

        return $this;
    }

    /**
     * Check if caching is enabled.
     */
    public function isEnabled(): bool
    {
        return $this->cacheEnabled;
    }

    /**
     * Check if cache exists and is valid.
     */
    public function exists(): bool
    {
        return $this->cacheEnabled && file_exists($this->cacheFile);
    }

    /**
     * Get cached routes.
     *
     * @return array<string, mixed>|null
     */
    public function get(): ?array
    {
        if (!$this->exists()) {
            return null;
        }

        try {
            $data = require $this->cacheFile;

            if (!is_array($data)) {
                return null;
            }

            return $data;
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Save routes to cache.
     *
     * @param array<string, mixed> $data
     *
     * @throws RuntimeException
     */
    public function put(array $data): void
    {
        if (!$this->cacheEnabled) {
            return;
        }

        // Create cache directory if it doesn't exist
        if (!is_dir($this->cacheDir) && (!mkdir($this->cacheDir, 0o755, true) && !is_dir($this->cacheDir))) {
            throw new RuntimeException('Failed to create cache directory: ' . $this->cacheDir);
        }

        // Generate cache content
        $content = $this->generateCacheContent($data);

        // Write to temporary file first, then rename (atomic operation)
        $tempFile = $this->cacheFile . '.' . uniqid('', true) . '.tmp';

        if (file_put_contents($tempFile, $content, LOCK_EX) === false) {
            throw new RuntimeException('Failed to write cache file: ' . $tempFile);
        }

        if (!rename($tempFile, $this->cacheFile)) {
            @unlink($tempFile);

            throw new RuntimeException(sprintf('Failed to rename cache file from %s to %s', $tempFile, $this->cacheFile));
        }

        // Set proper permissions
        @chmod($this->cacheFile, 0o644);
    }

    /**
     * Clear the cache.
     */
    public function clear(): bool
    {
        if (file_exists($this->cacheFile)) {
            return @unlink($this->cacheFile);
        }

        return true;
    }

    /**
     * Get cache file path.
     */
    public function getCacheFile(): string
    {
        return $this->cacheFile;
    }

    /**
     * Get cache directory.
     */
    public function getCacheDir(): string
    {
        return $this->cacheDir;
    }

    /**
     * Set custom cache file path.
     */
    public function setCacheFile(string $path): self
    {
        $this->cacheFile = $path;
        $this->cacheDir = dirname($path);

        return $this;
    }

    /**
     * Check if cache is fresh based on source files modification time.
     *
     * @param array<string> $sourceFiles
     */
    public function isFresh(array $sourceFiles = []): bool
    {
        if (!$this->exists()) {
            return false;
        }

        $cacheTime = filemtime($this->cacheFile);

        if ($cacheTime === false) {
            return false;
        }

        // Check if any source file is newer than cache
        foreach ($sourceFiles as $file) {
            if (file_exists($file) && filemtime($file) > $cacheTime) {
                return false;
            }
        }

        return true;
    }

    /**
     * Generate cache file content.
     *
     * @param array<string, mixed> $data
     */
    private function generateCacheContent(array $data): string
    {
        $export = var_export($data, true);

        return <<<PHP
            <?php

            declare(strict_types=1);

            /**
             * Router cache file
             * Generated: {$this->getCurrentDateTime()}
             */

            return {$export};

            PHP;
    }

    /**
     * Get current date and time for cache header.
     */
    private function getCurrentDateTime(): string
    {
        return date('Y-m-d H:i:s');
    }
}
