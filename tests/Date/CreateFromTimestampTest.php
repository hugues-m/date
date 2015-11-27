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

class CreateFromTimestampTest extends AbstractTestCase
{
    public function testCreateReturnsDatingInstance()
    {
        $d = Date::createFromTimestamp(Date::create(1975, 5, 21, 22, 32, 5)->getTimestamp());
        $this->assertDate($d, 1975, 5, 21, 22, 32, 5);
    }

    public function testCreateFromTimestampUsesDefaultTimezone()
    {
        $d = Date::createFromTimestamp(0);

        // We know Toronto is -5 since no DST in Jan
        $this->assertSame(1969, $d->getYear());
        $this->assertSame(-5 * 3600, $d->getOffset());
    }

    public function testCreateFromTimestampWithDateTimeZone()
    {
        $d = Date::createFromTimestamp(0, new \DateTimeZone('UTC'));
        $this->assertSame('UTC', $d->getTimezoneName());
        $this->assertDate($d, 1970, 1, 1, 0, 0, 0);
    }

    public function testCreateFromTimestampWithString()
    {
        $d = Date::createFromTimestamp(0, 'UTC');
        $this->assertDate($d, 1970, 1, 1, 0, 0, 0);
        $this->assertSame(0, $d->getOffset());
        $this->assertSame('UTC', $d->getTimezoneName());
    }

    public function testCreateFromTimestampGMTDoesNotUseDefaultTimezone()
    {
        $d = Date::createFromTimestampUTC(0);
        $this->assertDate($d, 1970, 1, 1, 0, 0, 0);
        $this->assertSame(0, $d->getOffset());
    }
}
