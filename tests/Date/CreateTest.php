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

class CreateTest extends AbstractTestCase
{
    public function testCreateReturnsDatingInstance()
    {
        $d = Date::create();
        $this->assertTrue($d instanceof Date);
    }

    public function testCreateWithDefaults()
    {
        $d = Date::create();
        $this->assertSame($d->getTimestamp(), Date::now()->getTimestamp());
    }

    public function testCreateWithYear()
    {
        $d = Date::create(2012);
        $this->assertSame(2012, $d->getYear());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateWithInvalidYear()
    {
        Date::create(-3);
    }

    public function testCreateWithMonth()
    {
        $d = Date::create(null, 3);
        $this->assertSame(3, $d->getMonth());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateWithInvalidMonth()
    {
        Date::create(null, -5);
    }

    public function testCreateMonthWraps()
    {
        $d = Date::create(2011, 0, 1, 0, 0, 0);
        $this->assertDate($d, 2010, 12, 1, 0, 0, 0);
    }

    public function testCreateWithDay()
    {
        $d = Date::create(null, null, 21);
        $this->assertSame(21, $d->getDay());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateWithInvalidDay()
    {
        Date::create(null, null, -4);
    }

    public function testCreateDayWraps()
    {
        $d = Date::create(2011, 1, 40, 0, 0, 0);
        $this->assertDate($d, 2011, 2, 9, 0, 0, 0);
    }

    public function testCreateWithHourAndDefaultMinSecToZero()
    {
        $d = Date::create(null, null, null, 14);
        $this->assertSame(14, $d->getHour());
        $this->assertSame(0, $d->getMinute());
        $this->assertSame(0, $d->getSecond());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateWithInvalidHour()
    {
        Date::create(null, null, null, -1);
    }

    public function testCreateHourWraps()
    {
        $d = Date::create(2011, 1, 1, 24, 0, 0);
        $this->assertDate($d, 2011, 1, 2, 0, 0, 0);
    }

    public function testCreateWithMinute()
    {
        $d = Date::create(null, null, null, null, 58);
        $this->assertSame(58, $d->getMinute());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateWithInvalidMinute()
    {
        Date::create(2011, 1, 1, 0, -2, 0);
    }

    public function testCreateMinuteWraps()
    {
        $d = Date::create(2011, 1, 1, 0, 62, 0);
        $this->assertDate($d, 2011, 1, 1, 1, 2, 0);
    }

    public function testCreateWithSecond()
    {
        $d = Date::create(null, null, null, null, null, 59);
        $this->assertSame(59, $d->getSecond());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateWithInvalidSecond()
    {
        Date::create(null, null, null, null, null, -2);
    }

    public function testCreateSecondsWrap()
    {
        $d = Date::create(2012, 1, 1, 0, 0, 61);
        $this->assertDate($d, 2012, 1, 1, 0, 1, 1);
    }

    public function testCreateWithDateTimeZone()
    {
        $d = Date::create(2012, 1, 1, 0, 0, 0, new \DateTimeZone('Europe/London'));
        $this->assertDate($d, 2012, 1, 1, 0, 0, 0);
        $this->assertSame('Europe/London', $d->getTimezoneName());
    }

    public function testCreateWithTimeZoneString()
    {
        $d = Date::create(2012, 1, 1, 0, 0, 0, 'Europe/London');
        $this->assertDate($d, 2012, 1, 1, 0, 0, 0);
        $this->assertSame('Europe/London', $d->getTimezoneName());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateWithInvalidTimezoneOffset()
    {
        Date::createFromDate(2000, 1, 1, 'Mars/Somewhere');
    }

    /**
     * @skip
     */
    public function testCreateWithValidTimezoneOffset()
    {
        $dt = Date::createFromDate(2000, 1, 1, -4);
        //$this->assertSame('America/New_York', $dt->getTimezoneName());
    }
}
