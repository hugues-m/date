<?php

namespace HMLB\Date\Tests\Date;

/*
 * This file is part of the Date package.
 *
 * (c) Hugues Maignol <hugues@hmlb.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use HMLB\Date\Date;
use HMLB\Date\Tests\AbstractTestCase;

class ConstructTest extends AbstractTestCase
{
    public function testCreatesAnInstanceDefaultToNow()
    {
        $c = new Date();
        $now = Date::now();
        $this->assertInstanceOfDate($c);
        $this->assertSame($now->getTimezoneName(), $c->getTimezoneName());
        $this->assertDate(
            $c,
            $now->getYear(),
            $now->getMonth(),
            $now->getDay(),
            $now->getHour(),
            $now->getMinute(),
            $now->getSecond()
        );
    }

    public function testParseCreatesAnInstanceDefaultToNow()
    {
        $c = Date::parse();
        $now = Date::now();
        $this->assertInstanceOfDate($c);
        $this->assertSame($now->getTimezoneName(), $c->getTimezoneName());
        $this->assertDate(
            $c,
            $now->getYear(),
            $now->getMonth(),
            $now->getDay(),
            $now->getHour(),
            $now->getMinute(),
            $now->getSecond()
        );
    }

    public function testWithFancyString()
    {
        $c = new Date('first day of January 2008');
        $this->assertDate($c, 2008, 1, 1, 0, 0, 0);
    }

    public function testParseWithFancyString()
    {
        $c = Date::parse('first day of January 2008');
        $this->assertDate($c, 2008, 1, 1, 0, 0, 0);
    }

    public function testDefaultTimezone()
    {
        $c = new Date('now');
        $this->assertSame('America/Toronto', $c->getTimezoneName());
    }

    public function testParseWithDefaultTimezone()
    {
        $c = Date::parse('now');
        $this->assertSame('America/Toronto', $c->getTimezoneName());
    }

    public function testSettingTimezone()
    {
        $timezone = 'Europe/London';
        $dtz = new \DateTimeZone($timezone);
        $dt = new \DateTime('now', $dtz);
        $dayLightSavingTimeOffset = (int) $dt->format('I');

        $c = new Date('now', $dtz);
        $this->assertSame($timezone, $c->getTimezoneName());
        $this->assertSame($dayLightSavingTimeOffset, $c->getOffsetHours());
    }

    public function testParseSettingTimezone()
    {
        $timezone = 'Europe/London';
        $dtz = new \DateTimeZone($timezone);
        $dt = new \DateTime('now', $dtz);
        $dayLightSavingTimeOffset = (int) $dt->format('I');

        $c = Date::parse('now', $dtz);
        $this->assertSame($timezone, $c->getTimezoneName());
        $this->assertSame($dayLightSavingTimeOffset, $c->getOffsetHours());
    }

    public function testSettingTimezoneWithString()
    {
        $timezone = 'Asia/Tokyo';
        $dtz = new \DateTimeZone($timezone);
        $dt = new \DateTime('now', $dtz);
        $dayLightSavingTimeOffset = (int) $dt->format('I');

        $c = new Date('now', $timezone);
        $this->assertSame($timezone, $c->getTimezoneName());
        $this->assertSame(9 + $dayLightSavingTimeOffset, $c->getOffsetHours());
    }

    public function testParseSettingTimezoneWithString()
    {
        $timezone = 'Asia/Tokyo';
        $dtz = new \DateTimeZone($timezone);
        $dt = new \DateTime('now', $dtz);
        $dayLightSavingTimeOffset = (int) $dt->format('I');

        $c = Date::parse('now', $timezone);
        $this->assertSame($timezone, $c->getTimezoneName());
        $this->assertSame(9 + $dayLightSavingTimeOffset, $c->getOffsetHours());
    }
}
