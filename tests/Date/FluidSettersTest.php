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

class FluidSettersTest extends AbstractTestCase
{
    public function testFluidYearSetter()
    {
        $d = Date::now()->year(1995);
        $this->assertTrue($d instanceof Date);
        $this->assertSame(1995, $d->getYear());
    }

    public function testFluidMonthSetter()
    {
        $d = Date::now()->month(3);
        $this->assertTrue($d instanceof Date);
        $this->assertSame(3, $d->getMonth());
    }

    public function testFluidMonthSetterWithWrap()
    {
        $d = Date::createFromDate(2012, 8, 21)->month(13);
        $this->assertTrue($d instanceof Date);
        $this->assertSame(1, $d->getMonth());
    }

    public function testFluidDaySetter()
    {
        $d = Date::now()->day(2);
        $this->assertTrue($d instanceof Date);
        $this->assertSame(2, $d->getDay());
    }

    public function testFluidDaySetterWithWrap()
    {
        $d = Date::createFromDate(2000, 1, 1)->day(32);
        $this->assertTrue($d instanceof Date);
        $this->assertSame(1, $d->getDay());
    }

    public function testFluidSetDate()
    {
        $d = Date::createFromDate(2000, 1, 1)->setDate(1995, 13, 32);
        $this->assertTrue($d instanceof Date);
        $this->assertDate($d, 1996, 2, 1);
    }

    public function testFluidHourSetter()
    {
        $d = Date::now()->hour(2);
        $this->assertTrue($d instanceof Date);
        $this->assertSame(2, $d->getHour());
    }

    public function testFluidHourSetterWithWrap()
    {
        $d = Date::now()->hour(25);
        $this->assertTrue($d instanceof Date);
        $this->assertSame(1, $d->getHour());
    }

    public function testFluidMinuteSetter()
    {
        $d = Date::now()->minute(2);
        $this->assertTrue($d instanceof Date);
        $this->assertSame(2, $d->getMinute());
    }

    public function testFluidMinuteSetterWithWrap()
    {
        $d = Date::now()->minute(61);
        $this->assertTrue($d instanceof Date);
        $this->assertSame(1, $d->getMinute());
    }

    public function testFluidSecondSetter()
    {
        $d = Date::now()->second(2);
        $this->assertTrue($d instanceof Date);
        $this->assertSame(2, $d->getSecond());
    }

    public function testFluidSecondSetterWithWrap()
    {
        $d = Date::now()->second(62);
        $this->assertTrue($d instanceof Date);
        $this->assertSame(2, $d->getSecond());
    }

    public function testFluidSetTime()
    {
        $d = Date::createFromDate(2000, 1, 1)->setTime(25, 61, 61);
        $this->assertTrue($d instanceof Date);
        $this->assertDate($d, 2000, 1, 2, 2, 2, 1);
    }

    public function testFluidTimestampSetter()
    {
        $d = Date::now()->timestamp(10);
        $this->assertTrue($d instanceof Date);
        $this->assertSame(10, $d->getTimestamp());
    }
}
