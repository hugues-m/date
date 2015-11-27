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

class DayOfWeekModifiersTest extends AbstractTestCase
{
    public function testGetWeekendDays()
    {
        $this->assertSame([Date::SATURDAY, Date::SUNDAY], Date::getWeekendDays());
    }

    public function testGetWeekEndsAt()
    {
        Date::setWeekEndsAt(Date::SATURDAY);
        $this->assertSame(Date::SATURDAY, Date::getWeekEndsAt());
        Date::setWeekEndsAt(Date::SUNDAY);
    }

    public function testGetWeekStartsAt()
    {
        Date::setWeekStartsAt(Date::TUESDAY);
        $this->assertSame(Date::TUESDAY, Date::getWeekStartsAt());
        Date::setWeekStartsAt(Date::MONDAY);
    }

    public function testStartOfWeek()
    {
        $d = Date::create(1980, 8, 7, 12, 11, 9)->startOfWeek();
        $this->assertDate($d, 1980, 8, 4, 0, 0, 0);
    }

    public function testStartOfWeekFromWeekStart()
    {
        $d = Date::createFromDate(1980, 8, 4)->startOfWeek();
        $this->assertDate($d, 1980, 8, 4, 0, 0, 0);
    }

    public function testStartOfWeekCrossingYearBoundary()
    {
        $d = Date::createFromDate(2013, 12, 31, 'GMT')->startOfWeek();
        $this->assertDate($d, 2013, 12, 30, 0, 0, 0);
    }

    public function testEndOfWeek()
    {
        $d = Date::create(1980, 8, 7, 11, 12, 13)->endOfWeek();
        $this->assertDate($d, 1980, 8, 10, 23, 59, 59);
    }

    public function testEndOfWeekFromWeekEnd()
    {
        $d = Date::createFromDate(1980, 8, 9)->endOfWeek();
        $this->assertDate($d, 1980, 8, 10, 23, 59, 59);
    }

    public function testEndOfWeekCrossingYearBoundary()
    {
        $d = Date::createFromDate(2013, 12, 31, 'GMT')->endOfWeek();
        $this->assertDate($d, 2014, 1, 5, 23, 59, 59);
    }

    public function testNext()
    {
        $d = Date::createFromDate(1975, 5, 21)->next();
        $this->assertDate($d, 1975, 5, 28, 0, 0, 0);
    }

    public function testNextMonday()
    {
        $d = Date::createFromDate(1975, 5, 21)->next(Date::MONDAY);
        $this->assertDate($d, 1975, 5, 26, 0, 0, 0);
    }

    public function testNextSaturday()
    {
        $d = Date::createFromDate(1975, 5, 21)->next(6);
        $this->assertDate($d, 1975, 5, 24, 0, 0, 0);
    }

    public function testNextTimestamp()
    {
        $d = Date::createFromDate(1975, 11, 14)->next();
        $this->assertDate($d, 1975, 11, 21, 0, 0, 0);
    }

    public function testPrevious()
    {
        $d = Date::createFromDate(1975, 5, 21)->previous();
        $this->assertDate($d, 1975, 5, 14, 0, 0, 0);
    }

    public function testPreviousMonday()
    {
        $d = Date::createFromDate(1975, 5, 21)->previous(Date::MONDAY);
        $this->assertDate($d, 1975, 5, 19, 0, 0, 0);
    }

    public function testPreviousSaturday()
    {
        $d = Date::createFromDate(1975, 5, 21)->previous(6);
        $this->assertDate($d, 1975, 5, 17, 0, 0, 0);
    }

    public function testPreviousTimestamp()
    {
        $d = Date::createFromDate(1975, 11, 28)->previous();
        $this->assertDate($d, 1975, 11, 21, 0, 0, 0);
    }

    public function testFirstDayOfMonth()
    {
        $d = Date::createFromDate(1975, 11, 21)->firstOfMonth();
        $this->assertDate($d, 1975, 11, 1, 0, 0, 0);
    }

    public function testFirstWednesdayOfMonth()
    {
        $d = Date::createFromDate(1975, 11, 21)->firstOfMonth(Date::WEDNESDAY);
        $this->assertDate($d, 1975, 11, 5, 0, 0, 0);
    }

    public function testFirstFridayOfMonth()
    {
        $d = Date::createFromDate(1975, 11, 21)->firstOfMonth(5);
        $this->assertDate($d, 1975, 11, 7, 0, 0, 0);
    }

    public function testLastDayOfMonth()
    {
        $d = Date::createFromDate(1975, 12, 5)->lastOfMonth();
        $this->assertDate($d, 1975, 12, 31, 0, 0, 0);
    }

    public function testLastTuesdayOfMonth()
    {
        $d = Date::createFromDate(1975, 12, 1)->lastOfMonth(Date::TUESDAY);
        $this->assertDate($d, 1975, 12, 30, 0, 0, 0);
    }

    public function testLastFridayOfMonth()
    {
        $d = Date::createFromDate(1975, 12, 5)->lastOfMonth(5);
        $this->assertDate($d, 1975, 12, 26, 0, 0, 0);
    }

    public function testNthOfMonthOutsideScope()
    {
        $this->assertFalse(Date::createFromDate(1975, 12, 5)->nthOfMonth(6, Date::MONDAY));
    }

    public function testNthOfMonthOutsideYear()
    {
        $this->assertFalse(Date::createFromDate(1975, 12, 5)->nthOfMonth(55, Date::MONDAY));
    }

    public function test2ndMondayOfMonth()
    {
        $d = Date::createFromDate(1975, 12, 5)->nthOfMonth(2, Date::MONDAY);
        $this->assertDate($d, 1975, 12, 8, 0, 0, 0);
    }

    public function test3rdWednesdayOfMonth()
    {
        $d = Date::createFromDate(1975, 12, 5)->nthOfMonth(3, 3);
        $this->assertDate($d, 1975, 12, 17, 0, 0, 0);
    }

    public function testFirstDayOfQuarter()
    {
        $d = Date::createFromDate(1975, 11, 21)->firstOfQuarter();
        $this->assertDate($d, 1975, 10, 1, 0, 0, 0);
    }

    public function testFirstWednesdayOfQuarter()
    {
        $d = Date::createFromDate(1975, 11, 21)->firstOfQuarter(Date::WEDNESDAY);
        $this->assertDate($d, 1975, 10, 1, 0, 0, 0);
    }

    public function testFirstFridayOfQuarter()
    {
        $d = Date::createFromDate(1975, 11, 21)->firstOfQuarter(5);
        $this->assertDate($d, 1975, 10, 3, 0, 0, 0);
    }

    public function testFirstOfQuarterFromADayThatWillNotExistIntheFirstMonth()
    {
        $d = Date::createFromDate(2014, 5, 31)->firstOfQuarter();
        $this->assertDate($d, 2014, 4, 1, 0, 0, 0);
    }

    public function testLastDayOfQuarter()
    {
        $d = Date::createFromDate(1975, 8, 5)->lastOfQuarter();
        $this->assertDate($d, 1975, 9, 30, 0, 0, 0);
    }

    public function testLastTuesdayOfQuarter()
    {
        $d = Date::createFromDate(1975, 8, 1)->lastOfQuarter(Date::TUESDAY);
        $this->assertDate($d, 1975, 9, 30, 0, 0, 0);
    }

    public function testLastFridayOfQuarter()
    {
        $d = Date::createFromDate(1975, 7, 5)->lastOfQuarter(5);
        $this->assertDate($d, 1975, 9, 26, 0, 0, 0);
    }

    public function testLastOfQuarterFromADayThatWillNotExistIntheLastMonth()
    {
        $d = Date::createFromDate(2014, 5, 31)->lastOfQuarter();
        $this->assertDate($d, 2014, 6, 30, 0, 0, 0);
    }

    public function testNthOfQuarterOutsideScope()
    {
        $this->assertFalse(Date::createFromDate(1975, 1, 5)->nthOfQuarter(20, Date::MONDAY));
    }

    public function testNthOfQuarterOutsideYear()
    {
        $this->assertFalse(Date::createFromDate(1975, 1, 5)->nthOfQuarter(55, Date::MONDAY));
    }

    public function testNthOfQuarterFromADayThatWillNotExistIntheFirstMonth()
    {
        $d = Date::createFromDate(2014, 5, 31)->nthOfQuarter(2, Date::MONDAY);
        $this->assertDate($d, 2014, 4, 14, 0, 0, 0);
    }

    public function test2ndMondayOfQuarter()
    {
        $d = Date::createFromDate(1975, 8, 5)->nthOfQuarter(2, Date::MONDAY);
        $this->assertDate($d, 1975, 7, 14, 0, 0, 0);
    }

    public function test3rdWednesdayOfQuarter()
    {
        $d = Date::createFromDate(1975, 8, 5)->nthOfQuarter(3, 3);
        $this->assertDate($d, 1975, 7, 16, 0, 0, 0);
    }

    public function testFirstDayOfYear()
    {
        $d = Date::createFromDate(1975, 11, 21)->firstOfYear();
        $this->assertDate($d, 1975, 1, 1, 0, 0, 0);
    }

    public function testFirstWednesdayOfYear()
    {
        $d = Date::createFromDate(1975, 11, 21)->firstOfYear(Date::WEDNESDAY);
        $this->assertDate($d, 1975, 1, 1, 0, 0, 0);
    }

    public function testFirstFridayOfYear()
    {
        $d = Date::createFromDate(1975, 11, 21)->firstOfYear(5);
        $this->assertDate($d, 1975, 1, 3, 0, 0, 0);
    }

    public function testLastDayOfYear()
    {
        $d = Date::createFromDate(1975, 8, 5)->lastOfYear();
        $this->assertDate($d, 1975, 12, 31, 0, 0, 0);
    }

    public function testLastTuesdayOfYear()
    {
        $d = Date::createFromDate(1975, 8, 1)->lastOfYear(Date::TUESDAY);
        $this->assertDate($d, 1975, 12, 30, 0, 0, 0);
    }

    public function testLastFridayOfYear()
    {
        $d = Date::createFromDate(1975, 7, 5)->lastOfYear(5);
        $this->assertDate($d, 1975, 12, 26, 0, 0, 0);
    }

    public function testNthOfYearOutsideScope()
    {
        $this->assertFalse(Date::createFromDate(1975, 1, 5)->nthOfYear(55, Date::MONDAY));
    }

    public function test2ndMondayOfYear()
    {
        $d = Date::createFromDate(1975, 8, 5)->nthOfYear(2, Date::MONDAY);
        $this->assertDate($d, 1975, 1, 13, 0, 0, 0);
    }

    public function test3rdWednesdayOfYear()
    {
        $d = Date::createFromDate(1975, 8, 5)->nthOfYear(3, 3);
        $this->assertDate($d, 1975, 1, 15, 0, 0, 0);
    }
}
