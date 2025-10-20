<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\BanManager;
use PHPUnit\Framework\TestCase;

class BanManagerTest extends TestCase
{
    private BanManager $banManager;

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

    public function testDefaultMaxViolationsIsThree(): void
    {
        $manager = new BanManager(); // Default: 3 violations
        $ip = '192.168.1.1';

        $manager->recordViolation($ip); // 1
        $this->assertFalse($manager->isBanned($ip));

        $manager->recordViolation($ip); // 2
        $this->assertFalse($manager->isBanned($ip));

        $shouldBan = $manager->recordViolation($ip); // 3 - should ban with default
        $this->assertTrue($shouldBan);
        $this->assertTrue($manager->isBanned($ip));
    }

    public function testDefaultBanDurationIs3600(): void
    {
        $manager = new BanManager(); // Default: 3600 seconds
        $ip = '192.168.1.1';

        $manager->ban($ip); // Use default duration

        $remaining = $manager->getBanTimeRemaining($ip);
        $this->assertGreaterThan(3590, $remaining);
        $this->assertLessThanOrEqual(3600, $remaining);
    }

    public function testMaxFunctionInGetBanTimeRemaining(): void
    {
        $ip = '192.168.1.1';

        // Test that max(0, ...) ensures non-negative result
        $remaining = $this->banManager->getBanTimeRemaining($ip);
        $this->assertGreaterThanOrEqual(0, $remaining);
    }

    public function testBanExactlyAtExpirationTime(): void
    {
        $ip = '192.168.1.1';

        $this->banManager->ban($ip, 1);
        $this->assertTrue($this->banManager->isBanned($ip));

        // Wait exactly 1 second
        sleep(1);

        // Should be unbanned (>= check in isBanned)
        $this->assertFalse($this->banManager->isBanned($ip));
    }

    public function testGetBannedIpsRemovesExpired(): void
    {
        $this->banManager->ban('192.168.1.1', 1); // Expires in 1 sec
        $this->banManager->ban('192.168.1.2', 3600); // Expires in 1 hour

        sleep(2); // Wait for first to expire

        $banned = $this->banManager->getBannedIps();

        // Only second IP should remain
        $this->assertCount(1, $banned);
        $this->assertArrayHasKey('192.168.1.2', $banned);
        $this->assertArrayNotHasKey('192.168.1.1', $banned);
    }

    public function testDifferentMaxViolations(): void
    {
        $manager = new BanManager(5, 3600); // 5 violations
        $ip = '192.168.1.1';

        // Should not ban after 3 violations (default was 3, now 5)
        $manager->recordViolation($ip); // 1
        $manager->recordViolation($ip); // 2
        $manager->recordViolation($ip); // 3
        $this->assertFalse($manager->isBanned($ip));

        $manager->recordViolation($ip); // 4
        $this->assertFalse($manager->isBanned($ip));

        $shouldBan = $manager->recordViolation($ip); // 5 - now ban
        $this->assertTrue($shouldBan);
        $this->assertTrue($manager->isBanned($ip));
    }

    public function testDifferentBanDuration(): void
    {
        $manager = new BanManager(3, 7200); // 2 hours
        $ip = '192.168.1.1';

        $manager->ban($ip); // Use default 7200

        $remaining = $manager->getBanTimeRemaining($ip);
        $this->assertGreaterThan(7190, $remaining);
        $this->assertLessThanOrEqual(7200, $remaining);
    }

    public function testGetBanTimeRemainingNeverNegative(): void
    {
        // Test that max(0, ...) ensures result is never negative
        $ip = '192.168.1.1';

        // Ban for very short time
        $this->banManager->ban($ip, 0);

        $remaining = $this->banManager->getBanTimeRemaining($ip);
        $this->assertGreaterThanOrEqual(0, $remaining);

        // Test with expired ban
        $this->banManager->ban($ip, 1);
        sleep(2);

        $remaining = $this->banManager->getBanTimeRemaining($ip);
        $this->assertEquals(0, $remaining);
    }

    public function testIsBannedCallsUnbanWhenExpired(): void
    {
        $ip = '192.168.1.1';

        // Ban for 1 second
        $this->banManager->ban($ip, 1);
        $this->assertTrue($this->banManager->isBanned($ip));

        // Wait for expiration
        sleep(2);

        // This call should unban the IP
        $isBanned = $this->banManager->isBanned($ip);
        $this->assertFalse($isBanned);

        // Verify it was actually unbanned (not in list)
        $bannedIps = $this->banManager->getBannedIps();
        $this->assertArrayNotHasKey($ip, $bannedIps);
    }

    public function testGreaterThanOrEqualInExpirationCheck(): void
    {
        $ip = '192.168.1.1';

        // Test that >= is used (not just >)
        $this->banManager->ban($ip, 1);
        $this->assertTrue($this->banManager->isBanned($ip));

        // At exactly expiration time (>= triggers, > doesn't)
        sleep(1);
        $this->assertFalse($this->banManager->isBanned($ip));
    }

    public function testGetBannedIpsUsesGreaterThanOrEqual(): void
    {
        // Test that getBannedIps() uses >= for expiration check
        $this->banManager->ban('192.168.1.1', 1);
        $this->banManager->ban('192.168.1.2', 3600);

        sleep(1); // Exactly at expiration of first ban

        $banned = $this->banManager->getBannedIps();

        // First should be removed (time >= expiration)
        $this->assertCount(1, $banned);
        $this->assertArrayHasKey('192.168.1.2', $banned);
    }

    protected function setUp(): void
    {
        $this->banManager = new BanManager(3, 3600); // 3 violations, 1 hour ban
    }
}
