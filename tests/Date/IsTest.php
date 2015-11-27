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

class IsTest extends AbstractTestCase
{
    public function testIsWeekdayTrue()
    {
        $this->assertTrue(Date::createFromDate(2012, 1, 2)->isWeekday());
    }

    public function testIsWeekdayFalse()
    {
        $this->assertFalse(Date::createFromDate(2012, 1, 1)->isWeekday());
    }

    public function testIsWeekendTrue()
    {
        $this->assertTrue(Date::createFromDate(2012, 1, 1)->isWeekend());
    }

    public function testIsWeekendFalse()
    {
        $this->assertFalse(Date::createFromDate(2012, 1, 2)->isWeekend());
    }

    public function testIsYesterdayTrue()
    {
        $this->assertTrue(Date::now()->subDay()->isYesterday());
    }

    public function testIsYesterdayFalseWithToday()
    {
        $this->assertFalse(Date::now()->endOfDay()->isYesterday());
    }

    public function testIsYesterdayFalseWith2Days()
    {
        $this->assertFalse(Date::now()->subDays(2)->startOfDay()->isYesterday());
    }

    public function testIsTodayTrue()
    {
        $this->assertTrue(Date::now()->isToday());
    }

    public function testIsTodayFalseWithYesterday()
    {
        $this->assertFalse(Date::now()->subDay()->endOfDay()->isToday());
    }

    public function testIsTodayFalseWithTomorrow()
    {
        $this->assertFalse(Date::now()->addDay()->startOfDay()->isToday());
    }

    public function testIsTodayWithTimezone()
    {
        $this->assertTrue(Date::now('Asia/Tokyo')->isToday());
    }

    public function testIsTomorrowTrue()
    {
        $this->assertTrue(Date::now()->addDay()->isTomorrow());
    }

    public function testIsTomorrowFalseWithToday()
    {
        $this->assertFalse(Date::now()->endOfDay()->isTomorrow());
    }

    public function testIsTomorrowFalseWith2Days()
    {
        $this->assertFalse(Date::now()->addDays(2)->startOfDay()->isTomorrow());
    }

    public function testIsFutureTrue()
    {
        $this->assertTrue(Date::now()->addSecond()->isFuture());
    }

    public function testIsFutureFalse()
    {
        $this->assertFalse(Date::now()->isFuture());
    }

    public function testIsFutureFalseInThePast()
    {
        $this->assertFalse(Date::now()->subSecond()->isFuture());
    }

    public function testIsPastTrue()
    {
        $this->assertTrue(Date::now()->subSecond()->isPast());
    }

    public function testIsPastFalse()
    {
        $this->assertFalse(Date::now()->addSecond()->isPast());
        $this->assertFalse(Date::now()->isPast());
    }

    public function testIsLeapYearTrue()
    {
        $this->assertTrue(Date::createFromDate(2016, 1, 1)->isLeapYear());
    }

    public function testIsLeapYearFalse()
    {
        $this->assertFalse(Date::createFromDate(2014, 1, 1)->isLeapYear());
    }

    public function testIsSameDayTrue()
    {
        $current = Date::createFromDate(2012, 1, 2);
        $this->assertTrue($current->isSameDay(Date::createFromDate(2012, 1, 2)));
    }

    public function testIsSameDayFalse()
    {
        $current = Date::createFromDate(2012, 1, 2);
        $this->assertFalse($current->isSameDay(Date::createFromDate(2012, 1, 3)));
    }

    public function testIsSunday()
    {
        // True in the past past
        $this->assertTrue(Date::createFromDate(2015, 5, 31)->isSunday());
        $this->assertTrue(Date::createFromDate(2015, 6, 21)->isSunday());
        $this->assertTrue(Date::now()->subWeek()->previous(Date::SUNDAY)->isSunday());

        // True in the future
        $this->assertTrue(Date::now()->addWeek()->previous(Date::SUNDAY)->isSunday());
        $this->assertTrue(Date::now()->addMonth()->previous(Date::SUNDAY)->isSunday());

        // False in the past
        $this->assertFalse(Date::now()->subWeek()->previous(Date::MONDAY)->isSunday());
        $this->assertFalse(Date::now()->subMonth()->previous(Date::MONDAY)->isSunday());

        // False in the future
        $this->assertFalse(Date::now()->addWeek()->previous(Date::MONDAY)->isSunday());
        $this->assertFalse(Date::now()->addMonth()->previous(Date::MONDAY)->isSunday());
    }

    public function testIsMonday()
    {
        // True in the past past
        $this->assertTrue(Date::createFromDate(2015, 6, 1)->isMonday());
        $this->assertTrue(Date::now()->subWeek()->previous(Date::MONDAY)->isMonday());

        // True in the future
        $this->assertTrue(Date::now()->addWeek()->previous(Date::MONDAY)->isMonday());
        $this->assertTrue(Date::now()->addMonth()->previous(Date::MONDAY)->isMonday());

        // False in the past
        $this->assertFalse(Date::now()->subWeek()->previous(Date::TUESDAY)->isMonday());
        $this->assertFalse(Date::now()->subMonth()->previous(Date::TUESDAY)->isMonday());

        // False in the future
        $this->assertFalse(Date::now()->addWeek()->previous(Date::TUESDAY)->isMonday());
        $this->assertFalse(Date::now()->addMonth()->previous(Date::TUESDAY)->isMonday());
    }

    public function testIsTuesday()
    {
        // True in the past past
        $this->assertTrue(Date::createFromDate(2015, 6, 2)->isTuesday());
        $this->assertTrue(Date::now()->subWeek()->previous(Date::TUESDAY)->isTuesday());

        // True in the future
        $this->assertTrue(Date::now()->addWeek()->previous(Date::TUESDAY)->isTuesday());
        $this->assertTrue(Date::now()->addMonth()->previous(Date::TUESDAY)->isTuesday());

        // False in the past
        $this->assertFalse(Date::now()->subWeek()->previous(Date::WEDNESDAY)->isTuesday());
        $this->assertFalse(Date::now()->subMonth()->previous(Date::WEDNESDAY)->isTuesday());

        // False in the future
        $this->assertFalse(Date::now()->addWeek()->previous(Date::WEDNESDAY)->isTuesday());
        $this->assertFalse(Date::now()->addMonth()->previous(Date::WEDNESDAY)->isTuesday());
    }

    public function testIsWednesday()
    {
        // True in the past past
        $this->assertTrue(Date::createFromDate(2015, 6, 3)->isWednesday());
        $this->assertTrue(Date::now()->subWeek()->previous(Date::WEDNESDAY)->isWednesday());

        // True in the future
        $this->assertTrue(Date::now()->addWeek()->previous(Date::WEDNESDAY)->isWednesday());
        $this->assertTrue(Date::now()->addMonth()->previous(Date::WEDNESDAY)->isWednesday());

        // False in the past
        $this->assertFalse(Date::now()->subWeek()->previous(Date::THURSDAY)->isWednesday());
        $this->assertFalse(Date::now()->subMonth()->previous(Date::THURSDAY)->isWednesday());

        // False in the future
        $this->assertFalse(Date::now()->addWeek()->previous(Date::THURSDAY)->isWednesday());
        $this->assertFalse(Date::now()->addMonth()->previous(Date::THURSDAY)->isWednesday());
    }

    public function testIsThursday()
    {
        // True in the past past
        $this->assertTrue(Date::createFromDate(2015, 6, 4)->isThursday());
        $this->assertTrue(Date::now()->subWeek()->previous(Date::THURSDAY)->isThursday());

        // True in the future
        $this->assertTrue(Date::now()->addWeek()->previous(Date::THURSDAY)->isThursday());
        $this->assertTrue(Date::now()->addMonth()->previous(Date::THURSDAY)->isThursday());

        // False in the past
        $this->assertFalse(Date::now()->subWeek()->previous(Date::FRIDAY)->isThursday());
        $this->assertFalse(Date::now()->subMonth()->previous(Date::FRIDAY)->isThursday());

        // False in the future
        $this->assertFalse(Date::now()->addWeek()->previous(Date::FRIDAY)->isThursday());
        $this->assertFalse(Date::now()->addMonth()->previous(Date::FRIDAY)->isThursday());
    }

    public function testIsFriday()
    {
        // True in the past past
        $this->assertTrue(Date::createFromDate(2015, 6, 5)->isFriday());
        $this->assertTrue(Date::now()->subWeek()->previous(Date::FRIDAY)->isFriday());

        // True in the future
        $this->assertTrue(Date::now()->addWeek()->previous(Date::FRIDAY)->isFriday());
        $this->assertTrue(Date::now()->addMonth()->previous(Date::FRIDAY)->isFriday());

        // False in the past
        $this->assertFalse(Date::now()->subWeek()->previous(Date::SATURDAY)->isFriday());
        $this->assertFalse(Date::now()->subMonth()->previous(Date::SATURDAY)->isFriday());

        // False in the future
        $this->assertFalse(Date::now()->addWeek()->previous(Date::SATURDAY)->isFriday());
        $this->assertFalse(Date::now()->addMonth()->previous(Date::SATURDAY)->isFriday());
    }

    public function testIsSaturday()
    {
        // True in the past past
        $this->assertTrue(Date::createFromDate(2015, 6, 6)->isSaturday());
        $this->assertTrue(Date::now()->subWeek()->previous(Date::SATURDAY)->isSaturday());

        // True in the future
        $this->assertTrue(Date::now()->addWeek()->previous(Date::SATURDAY)->isSaturday());
        $this->assertTrue(Date::now()->addMonth()->previous(Date::SATURDAY)->isSaturday());

        // False in the past
        $this->assertFalse(Date::now()->subWeek()->previous(Date::SUNDAY)->isSaturday());
        $this->assertFalse(Date::now()->subMonth()->previous(Date::SUNDAY)->isSaturday());

        // False in the future
        $this->assertFalse(Date::now()->addWeek()->previous(Date::SUNDAY)->isSaturday());
        $this->assertFalse(Date::now()->addMonth()->previous(Date::SUNDAY)->isSaturday());
    }
}
