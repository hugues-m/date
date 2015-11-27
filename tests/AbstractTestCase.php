<?php

namespace HMLB\Date\Tests;

use Closure;
use HMLB\Date\Date;
use HMLB\Date\Interval;
use PHPUnit_Framework_TestCase;

abstract class AbstractTestCase extends PHPUnit_Framework_TestCase
{
    private $saveTz;

    protected function setUp()
    {
        //save current timezone
        $this->saveTz = date_default_timezone_get();

        date_default_timezone_set('America/Toronto');
    }

    protected function tearDown()
    {
        date_default_timezone_set($this->saveTz);
    }

    protected function assertDate(Date $d, $year, $month, $day, $hour = null, $minute = null, $second = null)
    {
        $this->assertSame($year, $d->getYear(), 'Date->year');
        $this->assertSame($month, $d->getMonth(), 'Date->month');
        $this->assertSame($day, $d->getDay(), 'Date->day');

        if ($hour !== null) {
            $this->assertSame($hour, $d->getHour(), 'Date->hour');
        }

        if ($minute !== null) {
            $this->assertSame($minute, $d->getMinute(), 'Date->minute');
        }

        if ($second !== null) {
            $this->assertSame($second, $d->getSecond(), 'Date->second');
        }
    }

    protected function assertInstanceOfDate($d)
    {
        $this->assertInstanceOf(Date::class, $d);
    }

    protected function assertInterval(
        Interval $ci,
        $years,
        $months = null,
        $days = null,
        $hours = null,
        $minutes = null,
        $seconds = null
    ) {
        $this->assertSame($years, $ci->years, 'Interval->years');

        if ($months !== null) {
            $this->assertSame($months, $ci->months, 'Interval->months');
        }

        if ($days !== null) {
            $this->assertSame($days, $ci->dayz, 'Interval->dayz');
        }

        if ($hours !== null) {
            $this->assertSame($hours, $ci->hours, 'Interval->hours');
        }

        if ($minutes !== null) {
            $this->assertSame($minutes, $ci->minutes, 'Interval->minutes');
        }

        if ($seconds !== null) {
            $this->assertSame($seconds, $ci->seconds, 'Interval->seconds');
        }
    }

    protected function assertInstanceOfInterval($d)
    {
        $this->assertInstanceOf('HMLB\Date\Interval', $d);
    }

    protected function wrapWithTestNow(Closure $func, Date $dt = null)
    {
        Date::setTestNow($dt ?: Date::now());
        $func();
        Date::setTestNow();
    }
}
