<?php

declare(strict_types=1);

namespace CloudCastle\Http\Router\Tests\Unit;

use CloudCastle\Http\Router\TimeUnit;
use PHPUnit\Framework\TestCase;

class TimeUnitTest extends TestCase
{
    public function testSecondValue(): void
    {
        $this->assertEquals(1, TimeUnit::SECOND->value);
        $this->assertEquals(5, TimeUnit::SECOND->toSeconds(5));
    }

    public function testMinuteValue(): void
    {
        $this->assertEquals(60, TimeUnit::MINUTE->value);
        $this->assertEquals(120, TimeUnit::MINUTE->toSeconds(2));
    }

    public function testHourValue(): void
    {
        $this->assertEquals(3600, TimeUnit::HOUR->value);
        $this->assertEquals(7200, TimeUnit::HOUR->toSeconds(2));
    }

    public function testDayValue(): void
    {
        $this->assertEquals(86400, TimeUnit::DAY->value);
        $this->assertEquals(172800, TimeUnit::DAY->toSeconds(2));
    }

    public function testWeekValue(): void
    {
        $this->assertEquals(604800, TimeUnit::WEEK->value);
        $this->assertEquals(1209600, TimeUnit::WEEK->toSeconds(2));
    }

    public function testMonthValue(): void
    {
        $this->assertEquals(2592000, TimeUnit::MONTH->value);
        $this->assertEquals(5184000, TimeUnit::MONTH->toSeconds(2));
    }

    public function testGetName(): void
    {
        $this->assertEquals('second', TimeUnit::SECOND->getName());
        $this->assertEquals('minute', TimeUnit::MINUTE->getName());
        $this->assertEquals('hour', TimeUnit::HOUR->getName());
        $this->assertEquals('day', TimeUnit::DAY->getName());
        $this->assertEquals('week', TimeUnit::WEEK->getName());
        $this->assertEquals('month', TimeUnit::MONTH->getName());
    }

    public function testGetPlural(): void
    {
        $this->assertEquals('seconds', TimeUnit::SECOND->getPlural());
        $this->assertEquals('minutes', TimeUnit::MINUTE->getPlural());
        $this->assertEquals('hours', TimeUnit::HOUR->getPlural());
        $this->assertEquals('days', TimeUnit::DAY->getPlural());
        $this->assertEquals('weeks', TimeUnit::WEEK->getPlural());
        $this->assertEquals('months', TimeUnit::MONTH->getPlural());
    }
}
