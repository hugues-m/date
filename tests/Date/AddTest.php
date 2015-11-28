<?php

namespace HMLB\Date\Tests\Date;

use HMLB\Date\Date;
use HMLB\Date\Tests\AbstractTestCase;

class AddTest extends AbstractTestCase
{
    public function testAddYearsPositive()
    {
        $this->assertSame(1976, Date::createFromDate(1975)->addYears(1)->getYear());
    }

    public function testAddYearsZero()
    {
        $this->assertSame(1975, Date::createFromDate(1975)->addYears(0)->getYear());
    }

    public function testAddYearsNegative()
    {
        $this->assertSame(1974, Date::createFromDate(1975)->addYears(-1)->getYear());
    }

    public function testAddYear()
    {
        $this->assertSame(1976, Date::createFromDate(1975)->addYear()->getYear());
    }

    public function testAddMonthsPositive()
    {
        $this->assertSame(1, Date::createFromDate(1975, 12)->addMonths(1)->getMonth());
    }

    public function testAddMonthsZero()
    {
        $this->assertSame(12, Date::createFromDate(1975, 12)->addMonths(0)->getMonth());
    }

    public function testAddMonthsNegative()
    {
        $this->assertSame(11, Date::createFromDate(1975, 12, 1)->addMonths(-1)->getMonth());
    }

    public function testAddMonth()
    {
        $this->assertSame(1, Date::createFromDate(1975, 12)->addMonth()->getMonth());
    }

    public function testAddMonthWithOverflow()
    {
        $this->assertSame(3, Date::createFromDate(2012, 1, 31)->addMonth()->getMonth());
    }

    public function testAddMonthsNoOverflowPositive()
    {
        $this->assertSame('2012-02-29', Date::createFromDate(2012, 1, 31)->addMonthNoOverflow()->toDateString());
        $this->assertSame('2012-03-31', Date::createFromDate(2012, 1, 31)->addMonthsNoOverflow(2)->toDateString());
        $this->assertSame('2012-03-29', Date::createFromDate(2012, 2, 29)->addMonthNoOverflow()->toDateString());
        $this->assertSame('2012-02-29', Date::createFromDate(2011, 12, 31)->addMonthsNoOverflow(2)->toDateString());
    }

    public function testAddMonthsNoOverflowZero()
    {
        $this->assertSame(12, Date::createFromDate(1975, 12)->addMonths(0)->getMonth());
    }

    public function testAddMonthsNoOverflowNegative()
    {
        $this->assertSame('2012-01-29', Date::createFromDate(2012, 2, 29)->addMonthsNoOverflow(-1)->toDateString());
        $this->assertSame('2012-01-31', Date::createFromDate(2012, 3, 31)->addMonthsNoOverflow(-2)->toDateString());
        $this->assertSame('2012-02-29', Date::createFromDate(2012, 3, 31)->addMonthsNoOverflow(-1)->toDateString());
        $this->assertSame('2011-12-31', Date::createFromDate(2012, 1, 31)->addMonthsNoOverflow(-1)->toDateString());
    }

    public function testAddDaysPositive()
    {
        $this->assertSame(1, Date::createFromDate(1975, 5, 31)->addDays(1)->getDay());
    }

    public function testAddDaysZero()
    {
        $this->assertSame(31, Date::createFromDate(1975, 5, 31)->addDays(0)->getDay());
    }

    public function testAddDaysNegative()
    {
        $this->assertSame(30, Date::createFromDate(1975, 5, 31)->addDays(-1)->getDay());
    }

    public function testAddDay()
    {
        $this->assertSame(1, Date::createFromDate(1975, 5, 31)->addDay()->getDay());
    }

    public function testAddWeekdaysPositive()
    {
        $dt = Date::create(2012, 1, 4, 13, 2, 1)->addWeekdays(9);

        $this->assertSame(17, $dt->getDay());

        // test for phpbug id 54909
        $this->assertSame(13, $dt->getHour());
        $this->assertSame(2, $dt->getMinute());
        $this->assertSame(1, $dt->getSecond());
    }

    public function testAddWeekdaysZero()
    {
        $this->assertSame(4, Date::createFromDate(2012, 1, 4)->addWeekdays(0)->getDay());
    }

    public function testAddWeekdaysNegative()
    {
        $this->assertSame(18, Date::createFromDate(2012, 1, 31)->addWeekdays(-9)->getDay());
    }

    public function testAddWeekday()
    {
        $this->assertSame(9, Date::createFromDate(2012, 1, 6)->addWeekday()->getDay());
    }

    public function testAddWeekdayDuringWeekend()
    {
        $this->assertSame(9, Date::createFromDate(2012, 1, 7)->addWeekday()->getDay());
    }

    public function testAddWeeksPositive()
    {
        $this->assertSame(28, Date::createFromDate(1975, 5, 21)->addWeeks(1)->getDay());
    }

    public function testAddWeeksZero()
    {
        $this->assertSame(21, Date::createFromDate(1975, 5, 21)->addWeeks(0)->getDay());
    }

    public function testAddWeeksNegative()
    {
        $this->assertSame(14, Date::createFromDate(1975, 5, 21)->addWeeks(-1)->getDay());
    }

    public function testAddWeek()
    {
        $this->assertSame(28, Date::createFromDate(1975, 5, 21)->addWeek()->getDay());
    }

    public function testAddHoursPositive()
    {
        $this->assertSame(1, Date::createFromTime(0)->addHours(1)->getHour());
    }

    public function testAddHoursZero()
    {
        $this->assertSame(0, Date::createFromTime(0)->addHours(0)->getHour());
    }

    public function testAddHoursNegative()
    {
        $this->assertSame(23, Date::createFromTime(0)->addHours(-1)->getHour());
    }

    public function testAddHour()
    {
        $this->assertSame(1, Date::createFromTime(0)->addHour()->getHour());
    }

    public function testAddMinutesPositive()
    {
        $this->assertSame(1, Date::createFromTime(0, 0)->addMinutes(1)->getMinute());
    }

    public function testAddMinutesZero()
    {
        $this->assertSame(0, Date::createFromTime(0, 0)->addMinutes(0)->getMinute());
    }

    public function testAddMinutesNegative()
    {
        $this->assertSame(59, Date::createFromTime(0, 0)->addMinutes(-1)->getMinute());
    }

    public function testAddMinute()
    {
        $this->assertSame(1, Date::createFromTime(0, 0)->addMinute()->getMinute());
    }

    public function testAddSecondsPositive()
    {
        $this->assertSame(1, Date::createFromTime(0, 0, 0)->addSeconds(1)->getSecond());
    }

    public function testAddSecondsZero()
    {
        $this->assertSame(0, Date::createFromTime(0, 0, 0)->addSeconds(0)->getSecond());
    }

    public function testAddSecondsNegative()
    {
        $this->assertSame(59, Date::createFromTime(0, 0, 0)->addSeconds(-1)->getSecond());
    }

    public function testAddSecond()
    {
        $this->assertSame(1, Date::createFromTime(0, 0, 0)->addSecond()->getSecond());
    }

    /***** Test non plural methods with non default args *****/

    public function testAddYearPassingArg()
    {
        $this->assertSame(1977, Date::createFromDate(1975)->addYear(2)->getYear());
    }

    public function testAddMonthPassingArg()
    {
        $this->assertSame(7, Date::createFromDate(1975, 5, 1)->addMonth(2)->getMonth());
    }

    public function testAddMonthsNoOverflowPassingArg()
    {
        $dt = Date::createFromDate(2010, 12, 31)->addMonthsNoOverflow(2);
        $this->assertSame(2011, $dt->getYear());
        $this->assertSame(2, $dt->getMonth());
        $this->assertSame(28, $dt->getDay());
    }

    public function testAddDayPassingArg()
    {
        $this->assertSame(12, Date::createFromDate(1975, 5, 10)->addDay(2)->getDay());
    }

    public function testAddHourPassingArg()
    {
        $this->assertSame(2, Date::createFromTime(0)->addHour(2)->getHour());
    }

    public function testAddMinutePassingArg()
    {
        $this->assertSame(2, Date::createFromTime(0)->addMinute(2)->getMinute());
    }

    public function testAddSecondPassingArg()
    {
        $this->assertSame(2, Date::createFromTime(0)->addSecond(2)->getSecond());
    }
}
