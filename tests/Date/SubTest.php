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

class SubTest extends AbstractTestCase
{
    public function testSubYearsPositive()
    {
        $this->assertSame(1974, Date::createFromDate(1975)->subYears(1)->getYear());
    }

    public function testSubYearsZero()
    {
        $this->assertSame(1975, Date::createFromDate(1975)->subYears(0)->getYear());
    }

    public function testSubYearsNegative()
    {
        $this->assertSame(1976, Date::createFromDate(1975)->subYears(-1)->getYear());
    }

    public function testSubYear()
    {
        $this->assertSame(1974, Date::createFromDate(1975)->subYear()->getYear());
    }

    public function testSubMonthsPositive()
    {
        $this->assertSame(12, Date::createFromDate(1975, 1, 1)->subMonths(1)->getMonth());
    }

    public function testSubMonthsZero()
    {
        $this->assertSame(1, Date::createFromDate(1975, 1, 1)->subMonths(0)->getMonth());
    }

    public function testSubMonthsNegative()
    {
        $this->assertSame(2, Date::createFromDate(1975, 1, 1)->subMonths(-1)->getMonth());
    }

    public function testSubMonth()
    {
        $this->assertSame(12, Date::createFromDate(1975, 1, 1)->subMonth()->getMonth());
    }

    public function testSubDaysPositive()
    {
        $this->assertSame(30, Date::createFromDate(1975, 5, 1)->subDays(1)->getDay());
    }

    public function testSubDaysZero()
    {
        $this->assertSame(1, Date::createFromDate(1975, 5, 1)->subDays(0)->getDay());
    }

    public function testSubDaysNegative()
    {
        $this->assertSame(2, Date::createFromDate(1975, 5, 1)->subDays(-1)->getDay());
    }

    public function testSubDay()
    {
        $this->assertSame(30, Date::createFromDate(1975, 5, 1)->subDay()->getDay());
    }

    public function testSubWeekdaysPositive()
    {
        $this->assertSame(22, Date::createFromDate(2012, 1, 4)->subWeekdays(9)->getDay());
    }

    public function testSubWeekdaysZero()
    {
        $this->assertSame(4, Date::createFromDate(2012, 1, 4)->subWeekdays(0)->getDay());
    }

    public function testSubWeekdaysNegative()
    {
        $this->assertSame(13, Date::createFromDate(2012, 1, 31)->subWeekdays(-9)->getDay());
    }

    public function testSubWeekday()
    {
        $this->assertSame(6, Date::createFromDate(2012, 1, 9)->subWeekday()->getDay());
    }

    public function testSubWeekdayDuringWeekend()
    {
        $this->assertSame(6, Date::createFromDate(2012, 1, 8)->subWeekday()->getDay());
    }

    public function testSubWeeksPositive()
    {
        $this->assertSame(14, Date::createFromDate(1975, 5, 21)->subWeeks(1)->getDay());
    }

    public function testSubWeeksZero()
    {
        $this->assertSame(21, Date::createFromDate(1975, 5, 21)->subWeeks(0)->getDay());
    }

    public function testSubWeeksNegative()
    {
        $this->assertSame(28, Date::createFromDate(1975, 5, 21)->subWeeks(-1)->getDay());
    }

    public function testSubWeek()
    {
        $this->assertSame(14, Date::createFromDate(1975, 5, 21)->subWeek()->getDay());
    }

    public function testSubHoursPositive()
    {
        $this->assertSame(23, Date::createFromTime(0)->subHours(1)->getHour());
    }

    public function testSubHoursZero()
    {
        $this->assertSame(0, Date::createFromTime(0)->subHours(0)->getHour());
    }

    public function testSubHoursNegative()
    {
        $this->assertSame(1, Date::createFromTime(0)->subHours(-1)->getHour());
    }

    public function testSubHour()
    {
        $this->assertSame(23, Date::createFromTime(0)->subHour()->getHour());
    }

    public function testSubMinutesPositive()
    {
        $this->assertSame(59, Date::createFromTime(0, 0)->subMinutes(1)->getMinute());
    }

    public function testSubMinutesZero()
    {
        $this->assertSame(0, Date::createFromTime(0, 0)->subMinutes(0)->getMinute());
    }

    public function testSubMinutesNegative()
    {
        $this->assertSame(1, Date::createFromTime(0, 0)->subMinutes(-1)->getMinute());
    }

    public function testSubMinute()
    {
        $this->assertSame(59, Date::createFromTime(0, 0)->subMinute()->getMinute());
    }

    public function testSubSecondsPositive()
    {
        $this->assertSame(59, Date::createFromTime(0, 0, 0)->subSeconds(1)->getSecond());
    }

    public function testSubSecondsZero()
    {
        $this->assertSame(0, Date::createFromTime(0, 0, 0)->subSeconds(0)->getSecond());
    }

    public function testSubSecondsNegative()
    {
        $this->assertSame(1, Date::createFromTime(0, 0, 0)->subSeconds(-1)->getSecond());
    }

    public function testSubSecond()
    {
        $this->assertSame(59, Date::createFromTime(0, 0, 0)->subSecond()->getSecond());
    }

    /***** Test non plural methods with non default args *****/

    public function testSubYearPassingArg()
    {
        $this->assertSame(1973, Date::createFromDate(1975)->subYear(2)->getYear());
    }

    public function testSubMonthPassingArg()
    {
        $this->assertSame(3, Date::createFromDate(1975, 5, 1)->subMonth(2)->getMonth());
    }

    public function testSubMonthNoOverflowPassingArg()
    {
        $dt = Date::createFromDate(2011, 4, 30)->subMonthNoOverflow(2);
        $this->assertSame(2, $dt->getMonth());
        $this->assertSame(28, $dt->getDay());
    }

    public function testSubDayPassingArg()
    {
        $this->assertSame(8, Date::createFromDate(1975, 5, 10)->subDay(2)->getDay());
    }

    public function testSubHourPassingArg()
    {
        $this->assertSame(22, Date::createFromTime(0)->subHour(2)->getHour());
    }

    public function testSubMinutePassingArg()
    {
        $this->assertSame(58, Date::createFromTime(0)->subMinute(2)->getMinute());
    }

    public function testSubSecondPassingArg()
    {
        $this->assertSame(58, Date::createFromTime(0)->subSecond(2)->getSecond());
    }
}
