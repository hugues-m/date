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

class TestingAidsTest extends AbstractTestCase
{
    public function testTestingAidsWithTestNowNotSet()
    {
        Date::setTestNow();

        $this->assertFalse(Date::hasTestNow());
        $this->assertNull(Date::getTestNow());
    }

    public function testTestingAidsWithTestNowSet()
    {
        $notNow = Date::yesterday();
        Date::setTestNow($notNow);

        $this->assertTrue(Date::hasTestNow());
        $this->assertSame($notNow, Date::getTestNow());
    }

    public function testConstructorWithTestValueSet()
    {
        $notNow = Date::yesterday();
        Date::setTestNow($notNow);

        $this->assertEquals($notNow, new Date());
        $this->assertEquals($notNow, new Date(null));
        $this->assertEquals($notNow, new Date(''));
        $this->assertEquals($notNow, new Date('now'));
    }

    public function testNowWithTestValueSet()
    {
        $notNow = Date::yesterday();
        Date::setTestNow($notNow);

        $this->assertEquals($notNow, Date::now());
    }

    public function testParseWithTestValueSet()
    {
        $notNow = Date::yesterday();
        Date::setTestNow($notNow);

        $this->assertEquals($notNow, Date::parse());
        $this->assertEquals($notNow, Date::parse(null));
        $this->assertEquals($notNow, Date::parse(''));
        $this->assertEquals($notNow, Date::parse('now'));
    }

    public function testParseRelativeWithTestValueSet()
    {
        $notNow = Date::parse('2013-09-01 05:15:05');
        Date::setTestNow($notNow);

        $this->assertSame('2013-09-01 05:10:05', Date::parse('5 minutes ago')->toDateTimeString());

        $this->assertSame('2013-08-25 05:15:05', Date::parse('1 week ago')->toDateTimeString());

        $this->assertSame('2013-09-02 00:00:00', Date::parse('tomorrow')->toDateTimeString());
        $this->assertSame('2013-08-31 00:00:00', Date::parse('yesterday')->toDateTimeString());

        $this->assertSame('2013-09-02 05:15:05', Date::parse('+1 day')->toDateTimeString());
        $this->assertSame('2013-08-31 05:15:05', Date::parse('-1 day')->toDateTimeString());

        $this->assertSame('2013-09-02 00:00:00', Date::parse('next monday')->toDateTimeString());
        $this->assertSame('2013-09-03 00:00:00', Date::parse('next tuesday')->toDateTimeString());
        $this->assertSame('2013-09-04 00:00:00', Date::parse('next wednesday')->toDateTimeString());
        $this->assertSame('2013-09-05 00:00:00', Date::parse('next thursday')->toDateTimeString());
        $this->assertSame('2013-09-06 00:00:00', Date::parse('next friday')->toDateTimeString());
        $this->assertSame('2013-09-07 00:00:00', Date::parse('next saturday')->toDateTimeString());
        $this->assertSame('2013-09-08 00:00:00', Date::parse('next sunday')->toDateTimeString());

        $this->assertSame('2013-08-26 00:00:00', Date::parse('last monday')->toDateTimeString());
        $this->assertSame('2013-08-27 00:00:00', Date::parse('last tuesday')->toDateTimeString());
        $this->assertSame('2013-08-28 00:00:00', Date::parse('last wednesday')->toDateTimeString());
        $this->assertSame('2013-08-29 00:00:00', Date::parse('last thursday')->toDateTimeString());
        $this->assertSame('2013-08-30 00:00:00', Date::parse('last friday')->toDateTimeString());
        $this->assertSame('2013-08-31 00:00:00', Date::parse('last saturday')->toDateTimeString());
        $this->assertSame('2013-08-25 00:00:00', Date::parse('last sunday')->toDateTimeString());

        $this->assertSame('2013-09-02 00:00:00', Date::parse('this monday')->toDateTimeString());
        $this->assertSame('2013-09-03 00:00:00', Date::parse('this tuesday')->toDateTimeString());
        $this->assertSame('2013-09-04 00:00:00', Date::parse('this wednesday')->toDateTimeString());
        $this->assertSame('2013-09-05 00:00:00', Date::parse('this thursday')->toDateTimeString());
        $this->assertSame('2013-09-06 00:00:00', Date::parse('this friday')->toDateTimeString());
        $this->assertSame('2013-09-07 00:00:00', Date::parse('this saturday')->toDateTimeString());
        $this->assertSame('2013-09-01 00:00:00', Date::parse('this sunday')->toDateTimeString());

        $this->assertSame('2013-10-01 05:15:05', Date::parse('first day of next month')->toDateTimeString());
        $this->assertSame('2013-09-30 05:15:05', Date::parse('last day of this month')->toDateTimeString());
    }

    public function testParseRelativeWithMinusSignsInDate()
    {
        $notNow = Date::parse('2013-09-01 05:15:05');
        Date::setTestNow($notNow);

        $this->assertSame('2000-01-03 00:00:00', Date::parse('2000-1-3')->toDateTimeString());
        $this->assertSame('2000-10-10 00:00:00', Date::parse('2000-10-10')->toDateTimeString());
    }

    public function testTimeZoneWithTestValueSet()
    {
        $notNow = Date::parse('2013-07-01 12:00:00', 'America/New_York');
        Date::setTestNow($notNow);

        $this->assertSame('2013-07-01T12:00:00-0400', Date::parse('now')->toIso8601String());
        $this->assertSame('2013-07-01T11:00:00-0500', Date::parse('now', 'America/Mexico_City')->toIso8601String());
        $this->assertSame('2013-07-01T09:00:00-0700', Date::parse('now', 'America/Vancouver')->toIso8601String());
    }
}
