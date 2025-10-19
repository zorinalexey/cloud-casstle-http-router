<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\RouteCache;
use PHPUnit\Framework\TestCase;

class RouteCacheTest extends TestCase
{
    private string $cacheDir;

    private RouteCache $cache;

    public function testCacheCreation(): void
    {
        $this->assertInstanceOf(RouteCache::class, $this->cache);
        $this->assertEquals($this->cacheDir, $this->cache->getCacheDir());
    }

    public function testCacheNotExistsInitially(): void
    {
        $this->assertFalse($this->cache->exists());
    }

    public function testPutAndGetCache(): void
    {
        $data = [
            'routes' => [
                ['uri' => '/users', 'methods' => ['GET']],
                ['uri' => '/posts', 'methods' => ['POST']],
            ],
            'metadata' => ['version' => '1.0'],
        ];

        $this->cache->put($data);
        $this->assertTrue($this->cache->exists());

        $cached = $this->cache->get();
        $this->assertEquals($data, $cached);
    }

    public function testClearCache(): void
    {
        $data = ['routes' => []];
        $this->cache->put($data);
        $this->assertTrue($this->cache->exists());

        $this->cache->clear();
        $this->assertFalse($this->cache->exists());
    }

    public function testEnableDisableCache(): void
    {
        $this->assertTrue($this->cache->isEnabled());

        $this->cache->setEnabled(false);
        $this->assertFalse($this->cache->isEnabled());

        $this->cache->setEnabled(true);
        $this->assertTrue($this->cache->isEnabled());
    }

    public function testCacheFreshness(): void
    {
        $sourceFile = tempnam(sys_get_temp_dir(), 'source');
        file_put_contents($sourceFile, 'test');

        $data = ['routes' => []];
        $this->cache->put($data);

        // Cache should be fresh
        $this->assertTrue($this->cache->isFresh([$sourceFile]));

        // Modify source file
        sleep(1);
        touch($sourceFile);

        // Cache should no longer be fresh
        $this->assertFalse($this->cache->isFresh([$sourceFile]));

        unlink($sourceFile);
    }

    public function testGetNonExistentCache(): void
    {
        $result = $this->cache->get();
        $this->assertNull($result);
    }

    public function testCustomCacheFile(): void
    {
        $customPath = sys_get_temp_dir() . '/custom-cache-' . uniqid() . '.php';
        $this->cache->setCacheFile($customPath);

        $this->assertEquals($customPath, $this->cache->getCacheFile());

        $data = ['test' => 'value'];
        $this->cache->put($data);

        $this->assertFileExists($customPath);
        unlink($customPath);
    }

    protected function setUp(): void
    {
        $this->cacheDir = sys_get_temp_dir() . '/router-test-' . uniqid();
        $this->cache = new RouteCache($this->cacheDir);
    }

    protected function tearDown(): void
    {
        $this->cache->clear();
        if (is_dir($this->cacheDir)) {
            @rmdir($this->cacheDir);
        }
    }
}
