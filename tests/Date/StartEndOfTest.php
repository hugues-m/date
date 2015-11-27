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

class StartEndOfTest extends AbstractTestCase
{
    public function testStartOfDay()
    {
        $dt = Date::now()->startOfDay();
        $this->assertTrue($dt instanceof Date);
        $this->assertDate($dt, $dt->getYear(), $dt->getMonth(), $dt->getDay(), 0, 0, 0);
    }

    public function testEndOfDay()
    {
        $dt = Date::now()->endOfDay();
        $this->assertTrue($dt instanceof Date);
        $this->assertDate($dt, $dt->getYear(), $dt->getMonth(), $dt->getDay(), 23, 59, 59);
    }

    public function testStartOfMonthIsFluid()
    {
        $dt = Date::now()->startOfMonth();
        $this->assertTrue($dt instanceof Date);
    }

    public function testStartOfMonthFromNow()
    {
        $dt = Date::now()->startOfMonth();
        $this->assertDate($dt, $dt->getYear(), $dt->getMonth(), 1, 0, 0, 0);
    }

    public function testStartOfMonthFromLastDay()
    {
        $dt = Date::create(2000, 1, 31, 2, 3, 4)->startOfMonth();
        $this->assertDate($dt, 2000, 1, 1, 0, 0, 0);
    }

    public function testStartOfYearIsFluid()
    {
        $dt = Date::now();
        $this->assertTrue($dt->startOfYear() instanceof Date);
    }

    public function testStartOfYearFromNow()
    {
        $dt = Date::now()->startOfYear();
        $this->assertDate($dt, $dt->getYear(), 1, 1, 0, 0, 0);
    }

    public function testStartOfYearFromFirstDay()
    {
        $dt = Date::create(2000, 1, 1, 1, 1, 1)->startOfYear();
        $this->assertDate($dt, 2000, 1, 1, 0, 0, 0);
    }

    public function testStartOfYearFromLastDay()
    {
        $dt = Date::create(2000, 12, 31, 23, 59, 59)->startOfYear();
        $this->assertDate($dt, 2000, 1, 1, 0, 0, 0);
    }

    public function testEndOfMonthIsFluid()
    {
        $dt = Date::now();
        $this->assertTrue($dt->endOfMonth() instanceof Date);
    }

    public function testEndOfMonth()
    {
        $dt = Date::create(2000, 1, 1, 2, 3, 4)->endOfMonth();
        $this->assertDate($dt, 2000, 1, 31, 23, 59, 59);
    }

    public function testEndOfMonthFromLastDay()
    {
        $dt = Date::create(2000, 1, 31, 2, 3, 4)->endOfMonth();
        $this->assertDate($dt, 2000, 1, 31, 23, 59, 59);
    }

    public function testEndOfYearIsFluid()
    {
        $dt = Date::now();
        $this->assertTrue($dt->endOfYear() instanceof Date);
    }

    public function testEndOfYearFromNow()
    {
        $dt = Date::now()->endOfYear();
        $this->assertDate($dt, $dt->getYear(), 12, 31, 23, 59, 59);
    }

    public function testEndOfYearFromFirstDay()
    {
        $dt = Date::create(2000, 1, 1, 1, 1, 1)->endOfYear();
        $this->assertDate($dt, 2000, 12, 31, 23, 59, 59);
    }

    public function testEndOfYearFromLastDay()
    {
        $dt = Date::create(2000, 12, 31, 23, 59, 59)->endOfYear();
        $this->assertDate($dt, 2000, 12, 31, 23, 59, 59);
    }

    public function testStartOfDecadeIsFluid()
    {
        $dt = Date::now();
        $this->assertTrue($dt->startOfDecade() instanceof Date);
    }

    public function testStartOfDecadeFromNow()
    {
        $dt = Date::now()->startOfDecade();
        $this->assertDate($dt, $dt->getYear() - $dt->getYear() % 10, 1, 1, 0, 0, 0);
    }

    public function testStartOfDecadeFromFirstDay()
    {
        $dt = Date::create(2000, 1, 1, 1, 1, 1)->startOfDecade();
        $this->assertDate($dt, 2000, 1, 1, 0, 0, 0);
    }

    public function testStartOfDecadeFromLastDay()
    {
        $dt = Date::create(2009, 12, 31, 23, 59, 59)->startOfDecade();
        $this->assertDate($dt, 2000, 1, 1, 0, 0, 0);
    }

    public function testEndOfDecadeIsFluid()
    {
        $dt = Date::now();
        $this->assertTrue($dt->endOfDecade() instanceof Date);
    }

    public function testEndOfDecadeFromNow()
    {
        $dt = Date::now()->endOfDecade();
        $this->assertDate($dt, $dt->getYear() - $dt->getYear() % 10 + 9, 12, 31, 23, 59, 59);
    }

    public function testEndOfDecadeFromFirstDay()
    {
        $dt = Date::create(2000, 1, 1, 1, 1, 1)->endOfDecade();
        $this->assertDate($dt, 2009, 12, 31, 23, 59, 59);
    }

    public function testEndOfDecadeFromLastDay()
    {
        $dt = Date::create(2009, 12, 31, 23, 59, 59)->endOfDecade();
        $this->assertDate($dt, 2009, 12, 31, 23, 59, 59);
    }

    public function testStartOfCenturyIsFluid()
    {
        $dt = Date::now();
        $this->assertTrue($dt->startOfCentury() instanceof Date);
    }

    public function testStartOfCenturyFromNow()
    {
        $now = Date::now();
        $dt = Date::now()->startOfCentury();
        $this->assertDate($dt, $now->getYear() - $now->getYear() % 100, 1, 1, 0, 0, 0);
    }

    public function testStartOfCenturyFromFirstDay()
    {
        $dt = Date::create(2000, 1, 1, 1, 1, 1)->startOfCentury();
        $this->assertDate($dt, 2000, 1, 1, 0, 0, 0);
    }

    public function testStartOfCenturyFromLastDay()
    {
        $dt = Date::create(2099, 12, 31, 23, 59, 59)->startOfCentury();
        $this->assertDate($dt, 2000, 1, 1, 0, 0, 0);
    }

    public function testEndOfCenturyIsFluid()
    {
        $dt = Date::now();
        $this->assertTrue($dt->endOfCentury() instanceof Date);
    }

    public function testEndOfCenturyFromNow()
    {
        $now = Date::now();
        $dt = Date::now()->endOfCentury();
        $this->assertDate($dt, $now->getYear() - $now->getYear() % 100 + 99, 12, 31, 23, 59, 59);
    }

    public function testEndOfCenturyFromFirstDay()
    {
        $dt = Date::create(2001, 1, 1, 1, 1, 1)->endOfCentury();
        $this->assertDate($dt, 2099, 12, 31, 23, 59, 59);
    }

    public function testEndOfCenturyFromLastDay()
    {
        $dt = Date::create(2099, 12, 31, 23, 59, 59)->endOfCentury();
        $this->assertDate($dt, 2099, 12, 31, 23, 59, 59);
    }

    public function testAverageIsFluid()
    {
        $dt = Date::now()->average();
        $this->assertTrue($dt instanceof Date);
    }

    public function testAverageFromSame()
    {
        $dt1 = Date::create(2000, 1, 31, 2, 3, 4);
        $dt2 = Date::create(2000, 1, 31, 2, 3, 4)->average($dt1);
        $this->assertDate($dt2, 2000, 1, 31, 2, 3, 4);
    }

    public function testAverageFromGreater()
    {
        $dt1 = Date::create(2000, 1, 1, 1, 1, 1);
        $dt2 = Date::create(2009, 12, 31, 23, 59, 59)->average($dt1);
        $this->assertDate($dt2, 2004, 12, 31, 12, 30, 30);
    }

    public function testAverageFromLower()
    {
        $dt1 = Date::create(2009, 12, 31, 23, 59, 59);
        $dt2 = Date::create(2000, 1, 1, 1, 1, 1)->average($dt1);
        $this->assertDate($dt2, 2004, 12, 31, 12, 30, 30);
    }
}
