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

class NowAndOtherStaticHelpersTest extends AbstractTestCase
{
    public function testNow()
    {
        $dt = Date::now();
        $this->assertSame(time(), $dt->getTimestamp());
    }

    public function testNowWithTimezone()
    {
        $dt = Date::now('Europe/London');
        $this->assertSame(time(), $dt->getTimestamp());
        $this->assertSame('Europe/London', $dt->getTimezoneName());
    }

    public function testToday()
    {
        $dt = Date::today();
        $this->assertSame(date('Y-m-d 00:00:00'), $dt->toDateTimeString());
    }

    public function testTodayWithTimezone()
    {
        $dt = Date::today('Europe/London');
        $dt2 = new \DateTime('now', new \DateTimeZone('Europe/London'));
        $this->assertSame($dt2->format('Y-m-d 00:00:00'), $dt->toDateTimeString());
    }

    public function testTomorrow()
    {
        $dt = Date::tomorrow();
        $dt2 = new \DateTime('tomorrow');
        $this->assertSame($dt2->format('Y-m-d 00:00:00'), $dt->toDateTimeString());
    }

    public function testTomorrowWithTimezone()
    {
        $dt = Date::tomorrow('Europe/London');
        $dt2 = new \DateTime('tomorrow', new \DateTimeZone('Europe/London'));
        $this->assertSame($dt2->format('Y-m-d 00:00:00'), $dt->toDateTimeString());
    }

    public function testYesterday()
    {
        $dt = Date::yesterday();
        $dt2 = new \DateTime('yesterday');
        $this->assertSame($dt2->format('Y-m-d 00:00:00'), $dt->toDateTimeString());
    }

    public function testYesterdayWithTimezone()
    {
        $dt = Date::yesterday('Europe/London');
        $dt2 = new \DateTime('yesterday', new \DateTimeZone('Europe/London'));
        $this->assertSame($dt2->format('Y-m-d 00:00:00'), $dt->toDateTimeString());
    }

    public function testMinValue()
    {
        $this->assertLessThanOrEqual(-2147483647, Date::minValue()->getTimestamp());
    }

    public function testMaxValue()
    {
        $this->assertGreaterThanOrEqual(2147483647, Date::maxValue()->getTimestamp());
    }
}
