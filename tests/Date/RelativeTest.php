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

class RelativeTest extends AbstractTestCase
{
    public function testSecondsSinceMidnight()
    {
        $d = Date::today()->addSeconds(30);
        $this->assertSame(30, $d->secondsSinceMidnight());

        $d = Date::today()->addDays(1);
        $this->assertSame(0, $d->secondsSinceMidnight());

        $d = Date::today()->addDays(1)->addSeconds(120);
        $this->assertSame(120, $d->secondsSinceMidnight());

        $d = Date::today()->addMonths(3)->addSeconds(42);
        $this->assertSame(42, $d->secondsSinceMidnight());
    }

    public function testSecondsUntilEndOfDay()
    {
        $d = Date::today()->endOfDay();
        $this->assertSame(0, $d->secondsUntilEndOfDay());

        $d = Date::today()->endOfDay()->subSeconds(60);
        $this->assertSame(60, $d->secondsUntilEndOfDay());

        $d = Date::create(2014, 10, 24, 12, 34, 56);
        $this->assertSame(41103, $d->secondsUntilEndOfDay());

        $d = Date::create(2014, 10, 24, 0, 0, 0);
        $this->assertSame(86399, $d->secondsUntilEndOfDay());
    }
}
