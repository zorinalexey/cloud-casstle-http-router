<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\BanManager;
use PHPUnit\Framework\TestCase;

class BanManagerTest extends TestCase
{
    private BanManager $banManager;

    protected function setUp(): void
    {
        $this->banManager = new BanManager(3, 3600); // 3 violations, 1 hour ban
    }

    public function testIpNotBannedInitially(): void
    {
        $this->assertFalse($this->banManager->isBanned('192.168.1.1'));
    }

    public function testRecordViolation(): void
    {
        $ip = '192.168.1.1';

        // First violation - not banned
        $shouldBan = $this->banManager->recordViolation($ip);
        $this->assertFalse($shouldBan);
        $this->assertEquals(1, $this->banManager->getViolationCount($ip));
    }

    public function testAutoBanAfterMaxViolations(): void
    {
        $ip = '192.168.1.1';

        // Record violations
        $this->banManager->recordViolation($ip); // 1
        $this->assertFalse($this->banManager->isBanned($ip));

        $this->banManager->recordViolation($ip); // 2
        $this->assertFalse($this->banManager->isBanned($ip));

        $shouldBan = $this->banManager->recordViolation($ip); // 3 - should ban
        $this->assertTrue($shouldBan);
        $this->assertTrue($this->banManager->isBanned($ip));
    }

    public function testManualBan(): void
    {
        $ip = '192.168.1.1';

        $this->banManager->ban($ip, 600); // 10 minutes

        $this->assertTrue($this->banManager->isBanned($ip));
        $this->assertGreaterThan(0, $this->banManager->getBanTimeRemaining($ip));
    }

    public function testUnban(): void
    {
        $ip = '192.168.1.1';

        $this->banManager->ban($ip);
        $this->assertTrue($this->banManager->isBanned($ip));

        $this->banManager->unban($ip);
        $this->assertFalse($this->banManager->isBanned($ip));
    }

    public function testBanExpiration(): void
    {
        $ip = '192.168.1.1';

        $this->banManager->ban($ip, 1); // 1 second
        $this->assertTrue($this->banManager->isBanned($ip));

        sleep(2);

        $this->assertFalse($this->banManager->isBanned($ip));
    }

    public function testGetBannedIps(): void
    {
        $this->banManager->ban('192.168.1.1');
        $this->banManager->ban('192.168.1.2');

        $banned = $this->banManager->getBannedIps();

        $this->assertCount(2, $banned);
        $this->assertArrayHasKey('192.168.1.1', $banned);
        $this->assertArrayHasKey('192.168.1.2', $banned);
    }

    public function testClearViolations(): void
    {
        $ip = '192.168.1.1';

        $this->banManager->recordViolation($ip);
        $this->assertEquals(1, $this->banManager->getViolationCount($ip));

        $this->banManager->clearViolations($ip);
        $this->assertEquals(0, $this->banManager->getViolationCount($ip));
    }

    public function testClearAllBans(): void
    {
        $this->banManager->ban('192.168.1.1');
        $this->banManager->ban('192.168.1.2');

        $this->banManager->clearAllBans();

        $this->assertFalse($this->banManager->isBanned('192.168.1.1'));
        $this->assertFalse($this->banManager->isBanned('192.168.1.2'));
    }

    public function testGetStatistics(): void
    {
        $this->banManager->ban('192.168.1.1');
        $this->banManager->recordViolation('192.168.1.2');

        $stats = $this->banManager->getStatistics();

        $this->assertArrayHasKey('total_banned', $stats);
        $this->assertArrayHasKey('total_violations', $stats);
        $this->assertEquals(1, $stats['total_banned']);
        $this->assertEquals(3600, $stats['ban_duration']);
    }

    public function testBanTimeRemaining(): void
    {
        $ip = '192.168.1.1';

        $this->banManager->ban($ip, 3600); // 1 hour

        $remaining = $this->banManager->getBanTimeRemaining($ip);
        $this->assertGreaterThan(3590, $remaining);
        $this->assertLessThanOrEqual(3600, $remaining);
    }

    public function testNoBanTimeForNonBannedIp(): void
    {
        $ip = '192.168.1.1';

        $remaining = $this->banManager->getBanTimeRemaining($ip);
        $this->assertEquals(0, $remaining);
    }
}
