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

use HMLB\Date\Date;
use HMLB\Date\Interval;
use HMLB\Date\Tests\AbstractTestCase;

class AddTest extends AbstractTestCase
{
    public function testAdd()
    {
        $ci = Interval::create(4, 3, 6, 7, 8, 10, 11)->add(new Interval('P2Y1M5DT22H33M44S'));
        //$this->assertInterval($ci, 6, 4, 54, 30, 43, 55);
    }

    public function testAddWithDiffInterval()
    {
        $diff = Date::now()->diff(Date::now()->addWeeks(3));
        $ci = Interval::create(4, 3, 6, 7, 8, 10, 11)->add($diff);
        $this->assertInterval($ci, 4, 3, 70, 8, 10, 11);
    }

    public function testAddWithNegativeDiffInterval()
    {
        $diff = Date::now()->diff(Date::now()->subWeeks(3));
        $ci = Interval::create(4, 3, 6, 7, 8, 10, 11)->add($diff);
        $this->assertInterval($ci, 4, 3, 28, 8, 10, 11);
    }
}
