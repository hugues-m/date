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
use InvalidArgumentException;

class SettersTest extends AbstractTestCase
{
    public function testYearSetter()
    {
        $d = Date::now()->year(1995);
        $this->assertSame(1995, $d->getYear());
    }

    public function testImmutable()
    {
        $d = Date::now()->year(1989);
        $d2 = $d->year(1995);
        $this->assertSame(1989, $d->getYear());
        $this->assertSame(1995, $d2->getYear());
    }

    public function testMonthSetter()
    {
        $d = Date::now()->month(3);
        $this->assertSame(3, $d->getMonth());
    }

    public function testMonthSetterWithWrap()
    {
        $d = Date::now()->month(13);
        $this->assertSame(1, $d->getMonth());
    }

    public function testDaySetter()
    {
        $d = Date::now()->day(2);
        $this->assertSame(2, $d->getDay());
    }

    public function testDaySetterWithWrap()
    {
        $d = Date::createFromDate(2012, 8, 5)->day(32);
        $this->assertSame(1, $d->getDay());
    }

    public function testHourSetter()
    {
        $d = Date::now()->hour(2);
        $this->assertSame(2, $d->getHour());
    }

    public function testHourSetterWithWrap()
    {
        $d = Date::now()->hour(25);
        $this->assertSame(1, $d->getHour());
    }

    public function testMinuteSetter()
    {
        $d = Date::now()->minute(2);
        $this->assertSame(2, $d->getMinute());
    }

    public function testMinuteSetterWithWrap()
    {
        $d = Date::now()->minute(65);
        $this->assertSame(5, $d->getMinute());
    }

    public function testSecondSetter()
    {
        $d = Date::now()->second(2);
        $this->assertSame(2, $d->getSecond());
    }

    public function testTimeSetter()
    {
        $d = Date::now()->setTime(1, 1, 1);
        $this->assertSame(1, $d->getSecond());
        $d = $d->setTime(1, 1);
        $this->assertSame(0, $d->getSecond());
    }

    public function testTimeSetterWithChaining()
    {
        $d = Date::now();
        $d = $d->setTime(2, 2, 2)->setTime(1, 1, 1);
        $this->assertInstanceOf(Date::class, $d);
        $this->assertSame(1, $d->getSecond());
        $d = $d->setTime(2, 2, 2)->setTime(1, 1);
        $this->assertInstanceOf(Date::class, $d);
        $this->assertSame(0, $d->getSecond());
    }

    public function testTimeSetterWithZero()
    {
        $d = Date::now();
        $d = $d->setTime(1, 1);
        $this->assertSame(0, $d->getSecond());
    }

    public function testDateTimeSetter()
    {
        $d = Date::now();
        $d = $d->setDateTime($d->getYear(), $d->getMonth(), $d->getDay(), 1, 1, 1);
        $this->assertSame(1, $d->getSecond());
    }

    public function testDateTimeSetterWithZero()
    {
        $d = Date::now();
        $d = $d->setDateTime($d->getYear(), $d->getMonth(), $d->getDay(), 1, 1);
        $this->assertSame(0, $d->getSecond());
    }

    public function testDateTimeSetterWithChaining()
    {
        $d = Date::now();
        $d = $d->setDateTime(2013, 9, 24, 17, 4, 29);
        $this->assertInstanceOf(Date::class, $d);
        $d = $d->setDateTime(2014, 10, 25, 18, 5, 30);
        $this->assertInstanceOf(Date::class, $d);
        $this->assertDate($d, 2014, 10, 25, 18, 5, 30);
    }

    public function testSecondSetterWithWrap()
    {
        $d = Date::now()->second(65);
        $this->assertSame(5, $d->getSecond());
    }

    public function testTimestampSetter()
    {
        $d = Date::now()->timestamp(10);
        $this->assertSame(10, $d->getTimestamp());

        $d = $d->setTimestamp(11);
        $this->assertSame(11, $d->getTimestamp());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetTimezoneWithInvalidTimezone()
    {
        $d = Date::now();
        $d->setTimezone('sdf');
    }

    public function testTimezoneWithInvalidTimezone()
    {
        $d = Date::now();

        try {
            $d->timezone('sdf');
            $this->fail('InvalidArgumentException has not been raised.');
        } catch (InvalidArgumentException $expected) {
        }

        try {
            $d->timezone('sdf');
            $this->fail('InvalidArgumentException has not been raised.');
        } catch (InvalidArgumentException $expected) {
            //
        }
    }

    public function testSetTimezoneUsingString()
    {
        $d = Date::now();
        $d = $d->setTimezone('America/Toronto');
        $this->assertSame('America/Toronto', $d->getTimezoneName());
    }

    public function testTimezoneUsingString()
    {
        $d = Date::now();
        $d = $d->setTimezone('America/Toronto');
        $this->assertSame('America/Toronto', $d->getTimezoneName());

        $d = $d->timezone('America/Vancouver');
        $this->assertSame('America/Vancouver', $d->getTimezoneName());
    }

    public function testSetTimezoneUsingDateTimeZone()
    {
        $d = Date::now();
        $d = $d->setTimezone(new \DateTimeZone('America/Toronto'));
        $this->assertSame('America/Toronto', $d->getTimezoneName());
    }

    public function testTimezoneUsingDateTimeZone()
    {
        $d = Date::now();
        $d = $d->setTimezone(new \DateTimeZone('America/Toronto'));
        $this->assertSame('America/Toronto', $d->getTimezoneName());

        $d = $d->timezone(new \DateTimeZone('America/Vancouver'));
        $this->assertSame('America/Vancouver', $d->getTimezoneName());
    }

    public function testSetTimeFromTimeString()
    {
        $d = Date::now();

        $d = $d->setTimeFromTimeString('09:15:30');

        $this->assertSame(9, $d->getHour());
        $this->assertSame(15, $d->getMinute());
        $this->assertSame(30, $d->getSecond());
    }
}
