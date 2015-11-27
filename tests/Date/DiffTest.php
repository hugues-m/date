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

use Closure;
use HMLB\Date\Date;
use HMLB\Date\Interval;
use HMLB\Date\Tests\AbstractTestCase;

class DiffTest extends AbstractTestCase
{
    protected function wrapWithTestNow(Closure $func, Date $dt = null)
    {
        parent::wrapWithTestNow($func, ($dt === null) ? Date::createFromDate(2012, 1, 1) : $dt);
    }

    public function testDiffInYearsPositive()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(1, $dt->diffInYears($dt->copy()->addYear()));
    }

    public function testDiffInYearsNegativeWithSign()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(-1, $dt->diffInYears($dt->copy()->subYear(), false));
    }

    public function testDiffInYearsNegativeNoSign()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(1, $dt->diffInYears($dt->copy()->subYear()));
    }

    public function testDiffInYearsVsDefaultNow()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame(1, Date::now()->subYear()->diffInYears());
            }
        );
    }

    public function testDiffInYearsEnsureIsTruncated()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(1, $dt->diffInYears($dt->copy()->addYear()->addMonths(7)));
    }

    public function testDiffInMonthsPositive()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(13, $dt->diffInMonths($dt->copy()->addYear()->addMonth()));
    }

    public function testDiffInMonthsNegativeWithSign()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(-11, $dt->diffInMonths($dt->copy()->subYear()->addMonth(), false));
    }

    public function testDiffInMonthsNegativeNoSign()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(11, $dt->diffInMonths($dt->copy()->subYear()->addMonth()));
    }

    public function testDiffInMonthsVsDefaultNow()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame(12, Date::now()->subYear()->diffInMonths());
            }
        );
    }

    public function testDiffInMonthsEnsureIsTruncated()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(1, $dt->diffInMonths($dt->copy()->addMonth()->addDays(16)));
    }

    public function testDiffInDaysPositive()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(366, $dt->diffInDays($dt->copy()->addYear()));
    }

    public function testDiffInDaysNegativeWithSign()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(-365, $dt->diffInDays($dt->copy()->subYear(), false));
    }

    public function testDiffInDaysNegativeNoSign()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(365, $dt->diffInDays($dt->copy()->subYear()));
    }

    public function testDiffInDaysVsDefaultNow()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame(7, Date::now()->subWeek()->diffInDays());
            }
        );
    }

    public function testDiffInDaysEnsureIsTruncated()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(1, $dt->diffInDays($dt->copy()->addDay()->addHours(13)));
    }

    public function testDiffInDaysFilteredPositiveWithMutated()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(
            5,
            $dt->diffInDaysFiltered(
                function (Date $date) {
                    return $date->getDayOfWeek() === 1;
                },
                $dt->copy()->endOfMonth()
            )
        );
    }

    public function testDiffInDaysFilteredPositiveWithSecondObject()
    {
        $dt1 = Date::createFromDate(2000, 1, 1);
        $dt2 = Date::createFromDate(2000, 1, 31);

        $this->assertSame(
            5,
            $dt1->diffInDaysFiltered(
                function (Date $date) {
                    return $date->getDayOfWeek() === Date::SUNDAY;
                },
                $dt2
            )
        );
    }

    public function testDiffInDaysFilteredNegativeNoSignWithMutated()
    {
        $dt = Date::createFromDate(2000, 1, 31);
        $this->assertSame(
            5,
            $dt->diffInDaysFiltered(
                function (Date $date) {
                    return $date->getDayOfWeek() === Date::SUNDAY;
                },
                $dt->copy()->startOfMonth()
            )
        );
    }

    public function testDiffInDaysFilteredNegativeNoSignWithSecondObject()
    {
        $dt1 = Date::createFromDate(2000, 1, 31);
        $dt2 = Date::createFromDate(2000, 1, 1);

        $this->assertSame(
            5,
            $dt1->diffInDaysFiltered(
                function (Date $date) {
                    return $date->getDayOfWeek() === Date::SUNDAY;
                },
                $dt2
            )
        );
    }

    public function testDiffInDaysFilteredNegativeWithSignWithMutated()
    {
        $dt = Date::createFromDate(2000, 1, 31);
        $this->assertSame(
            -5,
            $dt->diffInDaysFiltered(
                function (Date $date) {
                    return $date->getDayOfWeek() === 1;
                },
                $dt->copy()->startOfMonth(),
                false
            )
        );
    }

    public function testDiffInDaysFilteredNegativeWithSignWithSecondObject()
    {
        $dt1 = Date::createFromDate(2000, 1, 31);
        $dt2 = Date::createFromDate(2000, 1, 1);

        $this->assertSame(
            -5,
            $dt1->diffInDaysFiltered(
                function (Date $date) {
                    return $date->getDayOfWeek() === Date::SUNDAY;
                },
                $dt2,
                false
            )
        );
    }

    public function testDiffInHoursFiltered()
    {
        $dt1 = Date::createFromDate(2000, 1, 31)->endOfDay();
        $dt2 = Date::createFromDate(2000, 1, 1)->startOfDay();

        $this->assertSame(
            31,
            $dt1->diffInHoursFiltered(
                function (Date $date) {
                    return $date->getHour() === 9;
                },
                $dt2
            )
        );
    }

    public function testDiffInHoursFilteredNegative()
    {
        $dt1 = Date::createFromDate(2000, 1, 31)->endOfDay();
        $dt2 = Date::createFromDate(2000, 1, 1)->startOfDay();

        $this->assertSame(
            -31,
            $dt1->diffInHoursFiltered(
                function (Date $date) {
                    return $date->getHour() === 9;
                },
                $dt2,
                false
            )
        );
    }

    public function testDiffInHoursFilteredWorkHoursPerWeek()
    {
        $dt1 = Date::createFromDate(2000, 1, 5)->endOfDay();
        $dt2 = Date::createFromDate(2000, 1, 1)->startOfDay();

        $this->assertSame(
            40,
            $dt1->diffInHoursFiltered(
                function (Date $date) {
                    return ($date->getHour() > 8 && $date->getHour() < 17);
                },
                $dt2
            )
        );
    }

    public function testDiffFilteredUsingMinutesPositiveWithMutated()
    {
        $dt = Date::createFromDate(2000, 1, 1)->startOfDay();
        $this->assertSame(
            60,
            $dt->diffFiltered(
                Interval::minute(),
                function (Date $date) {
                    return $date->getHour() === 12;
                },
                Date::createFromDate(2000, 1, 1)->endOfDay()
            )
        );
    }

    public function testDiffFilteredPositiveWithSecondObject()
    {
        $dt1 = Date::create(2000, 1, 1);
        $dt2 = $dt1->copy()->addSeconds(80);

        $this->assertSame(
            40,
            $dt1->diffFiltered(
                Interval::second(),
                function (Date $date) {
                    return $date->getSecond() % 2 === 0;
                },
                $dt2
            )
        );
    }

    public function testDiffFilteredNegativeNoSignWithMutated()
    {
        $dt = Date::createFromDate(2000, 1, 31);

        $this->assertSame(
            2,
            $dt->diffFiltered(
                Interval::days(2),
                function (Date $date) {
                    return $date->getDayOfWeek() === Date::SUNDAY;
                },
                $dt->copy()->startOfMonth()
            )
        );
    }

    public function testDiffFilteredNegativeNoSignWithSecondObject()
    {
        $dt1 = Date::createFromDate(2006, 1, 31);
        $dt2 = Date::createFromDate(2000, 1, 1);

        $this->assertSame(
            7,
            $dt1->diffFiltered(
                Interval::year(),
                function (Date $date) {
                    return $date->getMonth() === 1;
                },
                $dt2
            )
        );
    }

    public function testDiffFilteredNegativeWithSignWithMutated()
    {
        $dt = Date::createFromDate(2000, 1, 31);
        $this->assertSame(
            -4,
            $dt->diffFiltered(
                Interval::week(),
                function (Date $date) {
                    return $date->getMonth() === 12;
                },
                $dt->copy()->subMonths(3),
                false
            )
        );
    }

    public function testDiffFilteredNegativeWithSignWithSecondObject()
    {
        $dt1 = Date::createFromDate(2001, 1, 31);
        $dt2 = Date::createFromDate(1999, 1, 1);

        $this->assertSame(
            -12,
            $dt1->diffFiltered(
                Interval::month(),
                function (Date $date) {
                    return $date->getYear() === 2000;
                },
                $dt2,
                false
            )
        );
    }

    public function testBug188DiffWithSameDates()
    {
        $start = Date::create(2014, 10, 8, 15, 20, 0);
        $end = $start->copy();

        $this->assertSame(0, $start->diffInDays($end));
        $this->assertSame(0, $start->diffInWeekdays($end));
    }

    public function testBug188DiffWithDatesOnlyHoursApart()
    {
        $start = Date::create(2014, 10, 8, 15, 20, 0);
        $end = $start->copy();

        $this->assertSame(0, $start->diffInDays($end));
        $this->assertSame(0, $start->diffInWeekdays($end));
    }

    public function testBug188DiffWithSameDates1DayApart()
    {
        $start = Date::create(2014, 10, 8, 15, 20, 0);
        $end = $start->copy()->addDay();

        $this->assertSame(1, $start->diffInDays($end));
        $this->assertSame(1, $start->diffInWeekdays($end));
    }

    public function testBug188DiffWithDatesOnTheWeekend()
    {
        $start = Date::create(2014, 1, 1, 0, 0, 0)->next(Date::SATURDAY);
        $end = $start->addDay();

        $this->assertSame(1, $start->diffInDays($end));
        $this->assertSame(0, $start->diffInWeekdays($end));
    }

    public function testDiffInWeekdaysPositive()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(21, $dt->diffInWeekdays($dt->copy()->endOfMonth()));
    }

    public function testDiffInWeekdaysNegativeNoSign()
    {
        $dt = Date::createFromDate(2000, 1, 31);
        $this->assertSame(21, $dt->diffInWeekdays($dt->copy()->startOfMonth()));
    }

    public function testDiffInWeekdaysNegativeWithSign()
    {
        $dt = Date::createFromDate(2000, 1, 31);
        $this->assertSame(-21, $dt->diffInWeekdays($dt->copy()->startOfMonth(), false));
    }

    public function testDiffInWeekendDaysPositive()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(10, $dt->diffInWeekendDays($dt->copy()->endOfMonth()));
    }

    public function testDiffInWeekendDaysNegativeNoSign()
    {
        $dt = Date::createFromDate(2000, 1, 31);
        $this->assertSame(10, $dt->diffInWeekendDays($dt->copy()->startOfMonth()));
    }

    public function testDiffInWeekendDaysNegativeWithSign()
    {
        $dt = Date::createFromDate(2000, 1, 31);
        $this->assertSame(-10, $dt->diffInWeekendDays($dt->copy()->startOfMonth(), false));
    }

    public function testDiffInWeeksPositive()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(52, $dt->diffInWeeks($dt->copy()->addYear()));
    }

    public function testDiffInWeeksNegativeWithSign()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(-52, $dt->diffInWeeks($dt->copy()->subYear(), false));
    }

    public function testDiffInWeeksNegativeNoSign()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(52, $dt->diffInWeeks($dt->copy()->subYear()));
    }

    public function testDiffInWeeksVsDefaultNow()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame(1, Date::now()->subWeek()->diffInWeeks());
            }
        );
    }

    public function testDiffInWeeksEnsureIsTruncated()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(0, $dt->diffInWeeks($dt->copy()->addWeek()->subDay()));
    }

    public function testDiffInHoursPositive()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(26, $dt->diffInHours($dt->copy()->addDay()->addHours(2)));
    }

    public function testDiffInHoursNegativeWithSign()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(-22, $dt->diffInHours($dt->copy()->subDay()->addHours(2), false));
    }

    public function testDiffInHoursNegativeNoSign()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(22, $dt->diffInHours($dt->copy()->subDay()->addHours(2)));
    }

    public function testDiffInHoursVsDefaultNow()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame(48, Date::now()->subDays(2)->diffInHours());
            },
            Date::create(2012, 1, 15)
        );
    }

    public function testDiffInHoursEnsureIsTruncated()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(1, $dt->diffInHours($dt->copy()->addHour()->addMinutes(31)));
    }

    public function testDiffInMinutesPositive()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(62, $dt->diffInMinutes($dt->copy()->addHour()->addMinutes(2)));
    }

    public function testDiffInMinutesPositiveAlot()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(1502, $dt->diffInMinutes($dt->copy()->addHours(25)->addMinutes(2)));
    }

    public function testDiffInMinutesNegativeWithSign()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(-58, $dt->diffInMinutes($dt->copy()->subHour()->addMinutes(2), false));
    }

    public function testDiffInMinutesNegativeNoSign()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(58, $dt->diffInMinutes($dt->copy()->subHour()->addMinutes(2)));
    }

    public function testDiffInMinutesVsDefaultNow()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame(60, Date::now()->subHour()->diffInMinutes());
            }
        );
    }

    public function testDiffInMinutesEnsureIsTruncated()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(1, $dt->diffInMinutes($dt->copy()->addMinute()->addSeconds(31)));
    }

    public function testDiffInSecondsPositive()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(62, $dt->diffInSeconds($dt->copy()->addMinute()->addSeconds(2)));
    }

    public function testDiffInSecondsPositiveAlot()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(7202, $dt->diffInSeconds($dt->copy()->addHours(2)->addSeconds(2)));
    }

    public function testDiffInSecondsNegativeWithSign()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(-58, $dt->diffInSeconds($dt->copy()->subMinute()->addSeconds(2), false));
    }

    public function testDiffInSecondsNegativeNoSign()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(58, $dt->diffInSeconds($dt->copy()->subMinute()->addSeconds(2)));
    }

    public function testDiffInSecondsVsDefaultNow()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame(3600, Date::now()->subHour()->diffInSeconds());
            }
        );
    }

    public function testDiffInSecondsEnsureIsTruncated()
    {
        $dt = Date::createFromDate(2000, 1, 1);
        $this->assertSame(1, $dt->diffInSeconds($dt->copy()->addSeconds(1.9)));
    }

    public function testDiffInSecondsWithTimezones()
    {
        $dtOttawa = Date::createFromDate(2000, 1, 1, 'America/Toronto');
        $dtVancouver = Date::createFromDate(2000, 1, 1, 'America/Vancouver');
        $this->assertSame(3 * 60 * 60, $dtOttawa->diffInSeconds($dtVancouver));
    }

    public function testDiffInSecondsWithTimezonesAndVsDefauisBefore()
    {
        $vanNow = Date::now('America/Vancouver');
        $hereNow = $vanNow->setTimezone(Date::now()->getTimezone());
        $vanNow = $vanNow->setTimezone(Date::now()->getTimezone());

        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($vanNow, $scope) {
                $scope->assertSame(0, $vanNow->diffInSeconds());
            },
            $hereNow
        );
    }

    public function testDiffForHumansNowAndSecond()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('1 second ago', Date::now()->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndSecondWithTimezone()
    {
        $vanNow = Date::now('America/Vancouver');
        $hereNow = $vanNow->setTimezone(Date::now()->getTimezone());

        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($vanNow, $scope) {
                $scope->assertSame('1 second ago', $vanNow->diffForHumans());
            },
            $hereNow
        );
    }

    public function testDiffForHumansNowAndSeconds()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('2 seconds ago', Date::now()->subSeconds(2)->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndNearlyMinute()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('59 seconds ago', Date::now()->subSeconds(59)->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndMinute()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('1 minute ago', Date::now()->subMinute()->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndMinutes()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('2 minutes ago', Date::now()->subMinutes(2)->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndNearlyHour()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('59 minutes ago', Date::now()->subMinutes(59)->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndHour()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('1 hour ago', Date::now()->subHour()->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndHours()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('2 hours ago', Date::now()->subHours(2)->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndNearlyDay()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('23 hours ago', Date::now()->subHours(23)->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndDay()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('1 day ago', Date::now()->subDay()->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndDays()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('2 days ago', Date::now()->subDays(2)->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndNearlyWeek()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('6 days ago', Date::now()->subDays(6)->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndWeek()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('1 week ago', Date::now()->subWeek()->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndWeeks()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('2 weeks ago', Date::now()->subWeeks(2)->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndNearlyMonth()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('3 weeks ago', Date::now()->subWeeks(3)->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndMonth()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('4 weeks ago', Date::now()->subWeeks(4)->diffForHumans());
                $scope->assertSame('1 month ago', Date::now()->subMonth()->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndMonths()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('2 months ago', Date::now()->subMonths(2)->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndNearlyYear()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('11 months ago', Date::now()->subMonths(11)->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndYear()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('1 year ago', Date::now()->subYear()->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndYears()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('2 years ago', Date::now()->subYears(2)->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndFutureSecond()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('1 second from now', Date::now()->addSecond()->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndFutureSeconds()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('2 seconds from now', Date::now()->addSeconds(2)->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndNearlyFutureMinute()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('59 seconds from now', Date::now()->addSeconds(59)->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndFutureMinute()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('1 minute from now', Date::now()->addMinute()->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndFutureMinutes()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('2 minutes from now', Date::now()->addMinutes(2)->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndNearlyFutureHour()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('59 minutes from now', Date::now()->addMinutes(59)->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndFutureHour()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('1 hour from now', Date::now()->addHour()->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndFutureHours()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('2 hours from now', Date::now()->addHours(2)->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndNearlyFutureDay()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('23 hours from now', Date::now()->addHours(23)->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndFutureDay()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('1 day from now', Date::now()->addDay()->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndFutureDays()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('2 days from now', Date::now()->addDays(2)->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndNearlyFutureWeek()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('6 days from now', Date::now()->addDays(6)->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndFutureWeek()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('1 week from now', Date::now()->addWeek()->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndFutureWeeks()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('2 weeks from now', Date::now()->addWeeks(2)->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndNearlyFutureMonth()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('3 weeks from now', Date::now()->addWeeks(3)->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndFutureMonth()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('4 weeks from now', Date::now()->addWeeks(4)->diffForHumans());
                $scope->assertSame('1 month from now', Date::now()->addMonth()->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndFutureMonths()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('2 months from now', Date::now()->addMonths(2)->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndNearlyFutureYear()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('11 months from now', Date::now()->addMonths(11)->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndFutureYear()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('1 year from now', Date::now()->addYear()->diffForHumans());
            }
        );
    }

    public function testDiffForHumansNowAndFutureYears()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('2 years from now', Date::now()->addYears(2)->diffForHumans());
            }
        );
    }

    public function testDiffForHumansOtherAndSecond()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('1 second before', Date::now()->diffForHumans(Date::now()->addSecond()));
            }
        );
    }

    public function testDiffForHumansOtherAndSeconds()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('2 seconds before', Date::now()->diffForHumans(Date::now()->addSeconds(2)));
            }
        );
    }

    public function testDiffForHumansOtherAndNearlyMinute()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('59 seconds before', Date::now()->diffForHumans(Date::now()->addSeconds(59)));
            }
        );
    }

    public function testDiffForHumansOtherAndMinute()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('1 minute before', Date::now()->diffForHumans(Date::now()->addMinute()));
            }
        );
    }

    public function testDiffForHumansOtherAndMinutes()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('2 minutes before', Date::now()->diffForHumans(Date::now()->addMinutes(2)));
            }
        );
    }

    public function testDiffForHumansOtherAndNearlyHour()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('59 minutes before', Date::now()->diffForHumans(Date::now()->addMinutes(59)));
            }
        );
    }

    public function testDiffForHumansOtherAndHour()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('1 hour before', Date::now()->diffForHumans(Date::now()->addHour()));
            }
        );
    }

    public function testDiffForHumansOtherAndHours()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('2 hours before', Date::now()->diffForHumans(Date::now()->addHours(2)));
            }
        );
    }

    public function testDiffForHumansOtherAndNearlyDay()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('23 hours before', Date::now()->diffForHumans(Date::now()->addHours(23)));
            }
        );
    }

    public function testDiffForHumansOtherAndDay()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('1 day before', Date::now()->diffForHumans(Date::now()->addDay()));
            }
        );
    }

    public function testDiffForHumansOtherAndDays()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('2 days before', Date::now()->diffForHumans(Date::now()->addDays(2)));
            }
        );
    }

    public function testDiffForHumansOtherAndNearlyWeek()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('6 days before', Date::now()->diffForHumans(Date::now()->addDays(6)));
            }
        );
    }

    public function testDiffForHumansOtherAndWeek()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('1 week before', Date::now()->diffForHumans(Date::now()->addWeek()));
            }
        );
    }

    public function testDiffForHumansOtherAndWeeks()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('2 weeks before', Date::now()->diffForHumans(Date::now()->addWeeks(2)));
            }
        );
    }

    public function testDiffForHumansOtherAndNearlyMonth()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('3 weeks before', Date::now()->diffForHumans(Date::now()->addWeeks(3)));
            }
        );
    }

    public function testDiffForHumansOtherAndMonth()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('4 weeks before', Date::now()->diffForHumans(Date::now()->addWeeks(4)));
                $scope->assertSame('1 month before', Date::now()->diffForHumans(Date::now()->addMonth()));
            }
        );
    }

    public function testDiffForHumansOtherAndMonths()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('2 months before', Date::now()->diffForHumans(Date::now()->addMonths(2)));
            }
        );
    }

    public function testDiffForHumansOtherAndNearlyYear()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('11 months before', Date::now()->diffForHumans(Date::now()->addMonths(11)));
            }
        );
    }

    public function testDiffForHumansOtherAndYear()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('1 year before', Date::now()->diffForHumans(Date::now()->addYear()));
            }
        );
    }

    public function testDiffForHumansOtherAndYears()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('2 years before', Date::now()->diffForHumans(Date::now()->addYears(2)));
            }
        );
    }

    public function testDiffForHumansOtherAndFutureSecond()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('1 second after', Date::now()->diffForHumans(Date::now()->subSecond()));
            }
        );
    }

    public function testDiffForHumansOtherAndFutureSeconds()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('2 seconds after', Date::now()->diffForHumans(Date::now()->subSeconds(2)));
            }
        );
    }

    public function testDiffForHumansOtherAndNearlyFutureMinute()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('59 seconds after', Date::now()->diffForHumans(Date::now()->subSeconds(59)));
            }
        );
    }

    public function testDiffForHumansOtherAndFutureMinute()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('1 minute after', Date::now()->diffForHumans(Date::now()->subMinute()));
            }
        );
    }

    public function testDiffForHumansOtherAndFutureMinutes()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('2 minutes after', Date::now()->diffForHumans(Date::now()->subMinutes(2)));
            }
        );
    }

    public function testDiffForHumansOtherAndNearlyFutureHour()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('59 minutes after', Date::now()->diffForHumans(Date::now()->subMinutes(59)));
            }
        );
    }

    public function testDiffForHumansOtherAndFutureHour()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('1 hour after', Date::now()->diffForHumans(Date::now()->subHour()));
            }
        );
    }

    public function testDiffForHumansOtherAndFutureHours()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('2 hours after', Date::now()->diffForHumans(Date::now()->subHours(2)));
            }
        );
    }

    public function testDiffForHumansOtherAndNearlyFutureDay()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('23 hours after', Date::now()->diffForHumans(Date::now()->subHours(23)));
            }
        );
    }

    public function testDiffForHumansOtherAndFutureDay()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('1 day after', Date::now()->diffForHumans(Date::now()->subDay()));
            }
        );
    }

    public function testDiffForHumansOtherAndFutureDays()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('2 days after', Date::now()->diffForHumans(Date::now()->subDays(2)));
            }
        );
    }

    public function testDiffForHumansOtherAndNearlyFutureWeek()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('6 days after', Date::now()->diffForHumans(Date::now()->subDays(6)));
            }
        );
    }

    public function testDiffForHumansOtherAndFutureWeek()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('1 week after', Date::now()->diffForHumans(Date::now()->subWeek()));
            }
        );
    }

    public function testDiffForHumansOtherAndFutureWeeks()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('2 weeks after', Date::now()->diffForHumans(Date::now()->subWeeks(2)));
            }
        );
    }

    public function testDiffForHumansOtherAndNearlyFutureMonth()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('3 weeks after', Date::now()->diffForHumans(Date::now()->subWeeks(3)));
            }
        );
    }

    public function testDiffForHumansOtherAndFutureMonth()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('4 weeks after', Date::now()->diffForHumans(Date::now()->subWeeks(4)));
                $scope->assertSame('1 month after', Date::now()->diffForHumans(Date::now()->subMonth()));
            }
        );
    }

    public function testDiffForHumansOtherAndFutureMonths()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('2 months after', Date::now()->diffForHumans(Date::now()->subMonths(2)));
            }
        );
    }

    public function testDiffForHumansOtherAndNearlyFutureYear()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('11 months after', Date::now()->diffForHumans(Date::now()->subMonths(11)));
            }
        );
    }

    public function testDiffForHumansOtherAndFutureYear()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('1 year after', Date::now()->diffForHumans(Date::now()->subYear()));
            }
        );
    }

    public function testDiffForHumansOtherAndFutureYears()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('2 years after', Date::now()->diffForHumans(Date::now()->subYears(2)));
            }
        );
    }

    public function testDiffForHumansAbsoluteSeconds()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('59 seconds', Date::now()->diffForHumans(Date::now()->subSeconds(59), true));
                $scope->assertSame('59 seconds', Date::now()->diffForHumans(Date::now()->addSeconds(59), true));
            }
        );
    }

    public function testDiffForHumansAbsoluteMinutes()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('30 minutes', Date::now()->diffForHumans(Date::now()->subMinutes(30), true));
                $scope->assertSame('30 minutes', Date::now()->diffForHumans(Date::now()->addMinutes(30), true));
            }
        );
    }

    public function testDiffForHumansAbsoluteHours()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('3 hours', Date::now()->diffForHumans(Date::now()->subHours(3), true));
                $scope->assertSame('3 hours', Date::now()->diffForHumans(Date::now()->addHours(3), true));
            }
        );
    }

    public function testDiffForHumansAbsoluteDays()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('2 days', Date::now()->diffForHumans(Date::now()->subDays(2), true));
                $scope->assertSame('2 days', Date::now()->diffForHumans(Date::now()->addDays(2), true));
            }
        );
    }

    public function testDiffForHumansAbsoluteWeeks()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('2 weeks', Date::now()->diffForHumans(Date::now()->subWeeks(2), true));
                $scope->assertSame('2 weeks', Date::now()->diffForHumans(Date::now()->addWeeks(2), true));
            }
        );
    }

    public function testDiffForHumansAbsoluteMonths()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('2 months', Date::now()->diffForHumans(Date::now()->subMonths(2), true));
                $scope->assertSame('2 months', Date::now()->diffForHumans(Date::now()->addMonths(2), true));
            }
        );
    }

    public function testDiffForHumansAbsoluteYears()
    {
        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $scope->assertSame('1 year', Date::now()->diffForHumans(Date::now()->subYears(1), true));
                $scope->assertSame('1 year', Date::now()->diffForHumans(Date::now()->addYears(1), true));
            }
        );
    }

    public function testDiffForHumansWithShorterMonthShouldStillBeAMonth()
    {
        $feb15 = Date::parse('2015-02-15');
        $mar15 = Date::parse('2015-03-15');
        $this->assertSame('1 month after', $mar15->diffForHumans($feb15));
    }
}
