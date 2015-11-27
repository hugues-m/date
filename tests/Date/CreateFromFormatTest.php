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

class CreateFromFormatTest extends AbstractTestCase
{
    public function testCreateFromFormatReturnsDate()
    {
        $d = Date::createFromFormat('Y-m-d H:i:s', '1975-05-21 22:32:11');
        $this->assertDate($d, 1975, 5, 21, 22, 32, 11);
        $this->assertTrue($d instanceof Date);
    }

    public function testCreateFromFormatWithTimezoneString()
    {
        $d = Date::createFromFormat('Y-m-d H:i:s', '1975-05-21 22:32:11', 'Europe/London');
        $this->assertDate($d, 1975, 5, 21, 22, 32, 11);
        $this->assertSame('Europe/London', $d->getTimezoneName());
    }

    public function testCreateFromFormatWithTimezone()
    {
        $d = Date::createFromFormat('Y-m-d H:i:s', '1975-05-21 22:32:11', new \DateTimeZone('Europe/London'));
        $this->assertDate($d, 1975, 5, 21, 22, 32, 11);
        $this->assertSame('Europe/London', $d->getTimezoneName());
    }

    public function testCreateFromFormatWithMillis()
    {
        $d = Date::createFromFormat('Y-m-d H:i:s.u', '1975-05-21 22:32:11.254687');
        $this->assertSame(254687, $d->getMicro());
    }
}
