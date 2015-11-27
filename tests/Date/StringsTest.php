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
use HMLB\Date\Tests\Date\Fixtures\MyDate;

class StringsTest extends AbstractTestCase
{
    public function testToString()
    {
        $d = Date::now();
        $this->assertSame(Date::now()->toDateTimeString(), ''.$d);
    }

    public function testSetToStringFormat()
    {
        Date::setToStringFormat('jS \o\f F, Y g:i:s a');
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('25th of December, 1975 2:15:16 pm', ''.$d);
    }

    public function testResetToStringFormat()
    {
        $d = Date::now();
        Date::setToStringFormat('123');
        Date::resetToStringFormat();
        $this->assertSame($d->toDateTimeString(), ''.$d);
    }

    public function testExtendedClassToString()
    {
        $d = MyDate::now();
        $this->assertSame($d->toDateTimeString(), ''.$d);
    }

    public function testToDateString()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25', $d->toDateString());
    }

    public function testToFormattedDateString()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Dec 25, 1975', $d->toFormattedDateString());
    }

    public function testToLocalizedFormattedDateString()
    {
        /****************
         *
         * Working out a Travis issue on how to set a different locale
         * other than EN to test this.
         *
         *
         * $cache = setlocale(LC_TIME, 0);
         * setlocale(LC_TIME, 'German');
         * $d = Date::create(1975, 12, 25, 14, 15, 16);
         * $this->assertSame('Donnerstag 25 Dezember 1975', $d->formatLocalized('%A %d %B %Y'));
         * setlocale(LC_TIME, $cache);
         *****************/
    }

    public function testToLocalizedFormattedTimezonedDateString()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16, 'Europe/London');
        $this->assertSame('Thursday 25 December 1975 14:15', $d->formatLocalized('%A %d %B %Y %H:%M'));
    }

    public function testToTimeString()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('14:15:16', $d->toTimeString());
    }

    public function testToDateTimeString()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25 14:15:16', $d->toDateTimeString());
    }

    public function testToDateTimeStringWithPaddedZeroes()
    {
        $d = Date::create(2000, 5, 2, 4, 3, 4);
        $this->assertSame('2000-05-02 04:03:04', $d->toDateTimeString());
    }

    public function testToDayDateTimeString()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, Dec 25, 1975 2:15 PM', $d->toDayDateTimeString());
    }

    public function testToAtomString()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25T14:15:16-05:00', $d->toAtomString());
    }

    public function testToCOOKIEString()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        if (Date::COOKIE === 'l, d-M-y H:i:s T') {
            $cookieString = 'Thursday, 25-Dec-75 14:15:16 EST';
        } else {
            $cookieString = 'Thursday, 25-Dec-1975 14:15:16 EST';
        }

        $this->assertSame($cookieString, $d->toCOOKIEString());
    }

    public function testToIso8601String()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25T14:15:16-0500', $d->toIso8601String());
    }

    public function testToRC822String()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 75 14:15:16 -0500', $d->toRfc822String());
    }

    public function testToRfc850String()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thursday, 25-Dec-75 14:15:16 EST', $d->toRfc850String());
    }

    public function testToRfc1036String()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 75 14:15:16 -0500', $d->toRfc1036String());
    }

    public function testToRfc1123String()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 1975 14:15:16 -0500', $d->toRfc1123String());
    }

    public function testToRfc2822String()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 1975 14:15:16 -0500', $d->toRfc2822String());
    }

    public function testToRfc3339String()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25T14:15:16-05:00', $d->toRfc3339String());
    }

    public function testToRssString()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('Thu, 25 Dec 1975 14:15:16 -0500', $d->toRssString());
    }

    public function testToW3cString()
    {
        $d = Date::create(1975, 12, 25, 14, 15, 16);
        $this->assertSame('1975-12-25T14:15:16-05:00', $d->toW3cString());
    }
}
