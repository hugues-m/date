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

class CreateFromDateTest extends AbstractTestCase
{
    public function testCreateFromDateWithDefaults()
    {
        $d = Date::createFromDate();
        $this->assertSame($d->getTimestamp(), Date::create(null, null, null, null, null, null)->getTimestamp());
    }

    public function testCreateFromDate()
    {
        $d = Date::createFromDate(1975, 5, 21);
        $this->assertDate($d, 1975, 5, 21);
    }

    public function testCreateFromDateWithYear()
    {
        $d = Date::createFromDate(1975);
        $this->assertSame(1975, $d->getYear());
    }

    public function testCreateFromDateWithMonth()
    {
        $d = Date::createFromDate(null, 5);
        $this->assertSame(5, $d->getMonth());
    }

    public function testCreateFromDateWithDay()
    {
        $d = Date::createFromDate(null, null, 21);
        $this->assertSame(21, $d->getDay());
    }

    public function testCreateFromDateWithTimezone()
    {
        $d = Date::createFromDate(1975, 5, 21, 'Europe/London');
        $this->assertDate($d, 1975, 5, 21);
        $this->assertSame('Europe/London', $d->getTimezoneName());
    }

    public function testCreateFromDateWithDateTimeZone()
    {
        $d = Date::createFromDate(1975, 5, 21, new \DateTimeZone('Europe/London'));
        $this->assertDate($d, 1975, 5, 21);
        $this->assertSame('Europe/London', $d->getTimezoneName());
    }
}
