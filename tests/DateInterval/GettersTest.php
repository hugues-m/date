<?php

namespace HMLB\Date\Tests\Interval;

/*
 * This file is part of the Date package.
 *
 * (c) Hugues Maignol <hugues@hmlb.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use HMLB\Date\Interval;
use HMLB\Date\Tests\AbstractTestCase;

class GettersTest extends AbstractTestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGettersThrowExceptionOnUnknownGetter()
    {
        Interval::year()->sdfsdfss;
    }

    public function testYearsGetter()
    {
        $d = Interval::create(4, 5, 6, 5, 8, 9, 10);
        $this->assertSame(4, $d->years);
    }

    public function testMonthsGetter()
    {
        $d = Interval::create(4, 5, 6, 5, 8, 9, 10);
        $this->assertSame(5, $d->months);
    }

    public function testWeeksGetter()
    {
        $d = Interval::create(4, 5, 6, 5, 8, 9, 10);
        $this->assertSame(6, $d->weeks);
    }

    public function testDayzExcludingWeeksGetter()
    {
        $d = Interval::create(4, 5, 6, 5, 8, 9, 10);
        $this->assertSame(5, $d->daysExcludeWeeks);
        $this->assertSame(5, $d->dayzExcludeWeeks);
    }

    public function testDayzGetter()
    {
        $d = Interval::create(4, 5, 6, 5, 8, 9, 10);
        $this->assertSame(6 * 7 + 5, $d->dayz);
    }

    public function testHoursGetter()
    {
        $d = Interval::create(4, 5, 6, 5, 8, 9, 10);
        $this->assertSame(8, $d->hours);
    }

    public function testMinutesGetter()
    {
        $d = Interval::create(4, 5, 6, 5, 8, 9, 10);
        $this->assertSame(9, $d->minutes);
    }

    public function testSecondsGetter()
    {
        $d = Interval::create(4, 5, 6, 5, 8, 9, 10);
        $this->assertSame(10, $d->seconds);
    }
}
