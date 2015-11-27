<?php

namespace HMLB\Date\Tests\Interval;

/*
 * This file is part of the Date package.
 *
 * (c) Hugues Maignol <hugues@hmlb.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use HMLB\Date\Interval;
use HMLB\Date\Tests\AbstractTestCase;

class SettersTest extends AbstractTestCase
{
    public function testYearsSetter()
    {
        $d = Interval::create(4, 5, 6, 5, 8, 9, 10);
        $d->years = 2;
        $this->assertSame(2, $d->years);
    }

    public function testMonthsSetter()
    {
        $d = Interval::create(4, 5, 6, 5, 8, 9, 10);
        $d->months = 11;
        $this->assertSame(11, $d->months);
    }

    public function testWeeksSetter()
    {
        $d = Interval::create(4, 5, 6, 5, 8, 9, 10);
        $d->weeks = 11;
        $this->assertSame(11, $d->weeks);
        $this->assertSame(7 * 11, $d->dayz);
    }

    public function testDayzSetter()
    {
        $d = Interval::create(4, 5, 6, 5, 8, 9, 10);
        $d->dayz = 11;
        $this->assertSame(11, $d->dayz);
        $this->assertSame(1, $d->weeks);
        $this->assertSame(4, $d->dayzExcludeWeeks);
    }

    public function testHoursSetter()
    {
        $d = Interval::create(4, 5, 6, 5, 8, 9, 10);
        $d->hours = 12;
        $this->assertSame(12, $d->hours);
    }

    public function testMinutesSetter()
    {
        $d = Interval::create(4, 5, 6, 5, 8, 9, 10);
        $d->minutes = 11;
        $this->assertSame(11, $d->minutes);
    }

    public function testSecondsSetter()
    {
        $d = Interval::create(4, 5, 6, 5, 8, 9, 10);
        $d->seconds = 34;
        $this->assertSame(34, $d->seconds);
    }

    public function testFluentSetters()
    {
        $ci = Interval::years(4)->months(2)->dayz(5)->hours(3)->minute()->seconds(59);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 4, 2, 5, 3, 1, 59);

        $ci = Interval::years(4)->months(2)->weeks(2)->hours(3)->minute()->seconds(59);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 4, 2, 14, 3, 1, 59);
    }

    public function testFluentSettersDaysOverwritesWeeks()
    {
        $ci = Interval::weeks(3)->days(5);
        $this->assertInterval($ci, 0, 0, 5, 0, 0, 0);
    }

    public function testFluentSettersWeeksOverwritesDays()
    {
        $ci = Interval::days(5)->weeks(3);
        $this->assertInterval($ci, 0, 0, 3 * 7, 0, 0, 0);
    }

    public function testFluentSettersWeeksAndDaysIsCumulative()
    {
        $ci = Interval::year(5)->weeksAndDays(2, 6);
        $this->assertInterval($ci, 5, 0, 20, 0, 0, 0);
        $this->assertSame(20, $ci->dayz);
        $this->assertSame(2, $ci->weeks);
        $this->assertSame(6, $ci->dayzExcludeWeeks);
    }
}
