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

class GettersTest extends AbstractTestCase
{
    public function testYearGetter()
    {
        $d = Date::create(1234, 5, 6, 7, 8, 9);
        $this->assertSame(1234, $d->getYear());
    }

    public function testYearIsoGetter()
    {
        $d = Date::createFromDate(2012, 12, 31);
        $this->assertSame(2013, $d->getYearIso());
    }

    public function testMonthGetter()
    {
        $d = Date::create(1234, 5, 6, 7, 8, 9);
        $this->assertSame(5, $d->getMonth());
    }

    public function testDayGetter()
    {
        $d = Date::create(1234, 5, 6, 7, 8, 9);
        $this->assertSame(6, $d->getDay());
    }

    public function testHourGetter()
    {
        $d = Date::create(1234, 5, 6, 7, 8, 9);
        $this->assertSame(7, $d->getHour());
    }

    public function testMinuteGetter()
    {
        $d = Date::create(1234, 5, 6, 7, 8, 9);
        $this->assertSame(8, $d->getMinute());
    }

    public function testSecondGetter()
    {
        $d = Date::create(1234, 5, 6, 7, 8, 9);
        $this->assertSame(9, $d->getSecond());
    }

    public function testMicroGetter()
    {
        $micro = 345678;
        $d = Date::parse('2014-01-05 12:34:11.'.$micro);
        $this->assertSame($micro, $d->getMicro());
    }

    public function testMicroGetterWithDefaultNow()
    {
        $d = Date::now();
        $this->assertSame(0, $d->getMicro());
    }

    public function testDayOfWeeGetter()
    {
        $d = Date::create(2012, 5, 7, 7, 8, 9);
        $this->assertSame(Date::MONDAY, $d->getDayOfWeek());
    }

    public function testDayOfYearGetter()
    {
        $d = Date::createFromDate(2012, 5, 7);
        $this->assertSame(127, $d->getDayOfYear());
    }

    public function testDaysInMonthGetter()
    {
        $d = Date::createFromDate(2012, 5, 7);
        $this->assertSame(31, $d->getDaysInMonth());
    }

    public function testTimestampGetter()
    {
        $d = Date::create()->setTimezone('GMT')->setDateTime(1970, 1, 1, 0, 0, 0)->getTimestamp();
        $this->assertSame(0, $d);
    }

    public function testGetAge()
    {
        $d = Date::now();
        $this->assertSame(0, $d->getAge());
    }

    public function testGetAgeWithRealAge()
    {
        $d = Date::createFromDate(1975, 5, 21);
        $age = intval(substr(date('Ymd') - date('Ymd', $d->getTimestamp()), 0, -4));

        $this->assertSame($age, $d->getAge());
    }

    public function testGetQuarterFirst()
    {
        $d = Date::createFromDate(2012, 1, 1);
        $this->assertSame(1, $d->getQuarter());
    }

    public function testGetQuarterFirstEnd()
    {
        $d = Date::createFromDate(2012, 3, 31);
        $this->assertSame(1, $d->getQuarter());
    }

    public function testGetQuarterSecond()
    {
        $d = Date::createFromDate(2012, 4, 1);
        $this->assertSame(2, $d->getQuarter());
    }

    public function testGetQuarterThird()
    {
        $d = Date::createFromDate(2012, 7, 1);
        $this->assertSame(3, $d->getQuarter());
    }

    public function testGetQuarterFourth()
    {
        $d = Date::createFromDate(2012, 10, 1);
        $this->assertSame(4, $d->getQuarter());
    }

    public function testGetQuarterFirstLast()
    {
        $d = Date::createFromDate(2012, 12, 31);
        $this->assertSame(4, $d->getQuarter());
    }

    public function testGetLocalTrue()
    {
        // Default timezone has been set to America/Toronto in AbstractTestCase.php
        // @see : http://en.wikipedia.org/wiki/List_of_UTC_time_offsets
        $this->assertTrue(Date::createFromDate(2012, 1, 1, 'America/Toronto')->getLocal());
        $this->assertTrue(Date::createFromDate(2012, 1, 1, 'America/New_York')->getLocal());
    }

    public function testGetLocalFalse()
    {
        $this->assertFalse(Date::createFromDate(2012, 7, 1, 'UTC')->getLocal());
        $this->assertFalse(Date::createFromDate(2012, 7, 1, 'Europe/London')->getLocal());
    }

    public function testGetUtcFalse()
    {
        $this->assertFalse(Date::createFromDate(2013, 1, 1, 'America/Toronto')->isUtc());
        $this->assertFalse(Date::createFromDate(2013, 1, 1, 'Europe/Paris')->isUtc());
    }

    public function testGetUtcTrue()
    {
        $this->assertTrue(Date::createFromDate(2013, 1, 1, 'Atlantic/Reykjavik')->isUtc());
        $this->assertTrue(Date::createFromDate(2013, 1, 1, 'Europe/Lisbon')->isUtc());
        $this->assertTrue(Date::createFromDate(2013, 1, 1, 'Africa/Casablanca')->isUtc());
        $this->assertTrue(Date::createFromDate(2013, 1, 1, 'Africa/Dakar')->isUtc());
        $this->assertTrue(Date::createFromDate(2013, 1, 1, 'Europe/Dublin')->isUtc());
        $this->assertTrue(Date::createFromDate(2013, 1, 1, 'Europe/London')->isUtc());
        $this->assertTrue(Date::createFromDate(2013, 1, 1, 'UTC')->isUtc());
        $this->assertTrue(Date::createFromDate(2013, 1, 1, 'GMT')->isUtc());
    }

    public function testGetDstFalse()
    {
        $this->assertFalse(Date::createFromDate(2012, 1, 1, 'America/Toronto')->getDst());
    }

    public function testGetDstTrue()
    {
        $this->assertTrue(Date::createFromDate(2012, 7, 1, 'America/Toronto')->getDst());
    }

    public function testOffsetForTorontoWithDST()
    {
        $this->assertSame(-18000, Date::createFromDate(2012, 1, 1, 'America/Toronto')->getOffset());
    }

    public function testOffsetForTorontoNoDST()
    {
        $this->assertSame(-14400, Date::createFromDate(2012, 6, 1, 'America/Toronto')->getOffset());
    }

    public function testOffsetForGMT()
    {
        $this->assertSame(0, Date::createFromDate(2012, 6, 1, 'GMT')->getOffset());
    }

    public function testOffsetHoursForTorontoWithDST()
    {
        $this->assertSame(-5, Date::createFromDate(2012, 1, 1, 'America/Toronto')->getOffsetHours());
    }

    public function testOffsetHoursForTorontoNoDST()
    {
        $this->assertSame(-4, Date::createFromDate(2012, 6, 1, 'America/Toronto')->getOffsetHours());
    }

    public function testOffsetHoursForGMT()
    {
        $this->assertSame(0, Date::createFromDate(2012, 6, 1, 'GMT')->getOffsetHours());
    }

    public function testIsLeapYearTrue()
    {
        $this->assertTrue(Date::createFromDate(2012, 1, 1)->isLeapYear());
    }

    public function testIsLeapYearFalse()
    {
        $this->assertFalse(Date::createFromDate(2011, 1, 1)->isLeapYear());
    }

    public function testWeekOfMonth()
    {
        $this->assertSame(5, Date::createFromDate(2012, 9, 30)->getWeekOfMonth());
        $this->assertSame(4, Date::createFromDate(2012, 9, 28)->getWeekOfMonth());
        $this->assertSame(3, Date::createFromDate(2012, 9, 20)->getWeekOfMonth());
        $this->assertSame(2, Date::createFromDate(2012, 9, 8)->getWeekOfMonth());
        $this->assertSame(1, Date::createFromDate(2012, 9, 1)->getWeekOfMonth());
    }

    public function testWeekOfYearFirstWeek()
    {
        $this->assertSame(52, Date::createFromDate(2012, 1, 1)->getWeekOfYear());
        $this->assertSame(1, Date::createFromDate(2012, 1, 2)->getWeekOfYear());
    }

    public function testWeekOfYearLastWeek()
    {
        $this->assertSame(52, Date::createFromDate(2012, 12, 30)->getWeekOfYear());
        $this->assertSame(1, Date::createFromDate(2012, 12, 31)->getWeekOfYear());
    }

    public function testGetTimezone()
    {
        $dt = Date::createFromDate(2000, 1, 1, 'America/Toronto');
        $this->assertSame('America/Toronto', $dt->getTimezone()->getName());

        $dt = Date::createFromDate(2000, 1, 1, -5);
        $this->assertSame('-05:00', $dt->getTimezone()->getName());
    }

    public function testGetTimezoneName()
    {
        $dt = Date::createFromDate(2000, 1, 1, 'America/Toronto');
        $this->assertSame('America/Toronto', $dt->getTimezoneName());

        $dt = Date::createFromDate(2000, 1, 1, -5);
        $this->assertSame('-05:00', $dt->getTimezoneName());
    }
}
