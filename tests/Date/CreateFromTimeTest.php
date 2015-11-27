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

class CreateFromTimeTest extends AbstractTestCase
{
    public function testCreateFromDateWithDefaults()
    {
        $d = Date::createFromTime();
        $this->assertSame($d->getTimestamp(), Date::create(null, null, null, null, null, null)->getTimestamp());
    }

    public function testCreateFromDate()
    {
        $d = Date::createFromTime(23, 5, 21);
        $this->assertDate($d, Date::now()->getYear(), Date::now()->getMonth(), Date::now()->getDay(), 23, 5, 21);
    }

    public function testCreateFromTimeWithHour()
    {
        $d = Date::createFromTime(22);
        $this->assertSame(22, $d->getHour());
        $this->assertSame(0, $d->getMinute());
        $this->assertSame(0, $d->getSecond());
    }

    public function testCreateFromTimeWithMinute()
    {
        $d = Date::createFromTime(null, 5);
        $this->assertSame(5, $d->getMinute());
    }

    public function testCreateFromTimeWithSecond()
    {
        $d = Date::createFromTime(null, null, 21);
        $this->assertSame(21, $d->getSecond());
    }

    public function testCreateFromTimeWithDateTimeZone()
    {
        $d = Date::createFromTime(12, 0, 0, new \DateTimeZone('Europe/London'));
        $this->assertDate($d, Date::now()->getYear(), Date::now()->getMonth(), Date::now()->getDay(), 12, 0, 0);
        $this->assertSame('Europe/London', $d->getTimezoneName());
    }

    public function testCreateFromTimeWithTimeZoneString()
    {
        $d = Date::createFromTime(12, 0, 0, 'Europe/London');
        $this->assertDate($d, Date::now()->getYear(), Date::now()->getMonth(), Date::now()->getDay(), 12, 0, 0);
        $this->assertSame('Europe/London', $d->getTimezoneName());
    }
}
