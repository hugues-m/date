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

use DateInterval;
use HMLB\Date\Date;
use HMLB\Date\Interval;
use HMLB\Date\Tests\AbstractTestCase;

class ConstructTest extends AbstractTestCase
{
    public function testDefaults()
    {
        $ci = new Interval();
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 1, 0, 0, 0, 0, 0);
    }

    public function testNulls()
    {
        $ci = new Interval(null, null, null, null, null, null);
        $this->assertInterval($ci, 0, 0, 0, 0, 0, 0);
        $ci = Interval::days(null);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 0, 0, 0, 0, 0, 0);
    }

    public function testZeroes()
    {
        $ci = new Interval(0, 0, 0, 0, 0, 0);
        $this->assertInterval($ci, 0, 0, 0, 0, 0, 0);

        $ci = Interval::days(0);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 0, 0, 0, 0, 0, 0);
    }

    public function testZeroesChained()
    {
        $ci = Interval::days(0)->week(0)->minutes(0);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 0, 0, 0, 0, 0, 0);
    }

    public function testYears()
    {
        $ci = new Interval(1);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 1, 0, 0, 0, 0, 0);

        $ci = Interval::years(2);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 2, 0, 0, 0, 0, 0);

        $ci = Interval::year();
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 1, 0, 0, 0, 0, 0);

        $ci = Interval::year(3);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 3, 0, 0, 0, 0, 0);
    }

    public function testMonths()
    {
        $ci = new Interval(0, 1);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 0, 1, 0, 0, 0, 0);

        $ci = Interval::months(2);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 0, 2, 0, 0, 0, 0);

        $ci = Interval::month();
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 0, 1, 0, 0, 0, 0);

        $ci = Interval::month(3);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 0, 3, 0, 0, 0, 0);
    }

    public function testWeeks()
    {
        $ci = new Interval(0, 0, 1);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 0, 0, 7, 0, 0, 0);

        $ci = Interval::weeks(2);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 0, 0, 14, 0, 0, 0);

        $ci = Interval::week();
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 0, 0, 7, 0, 0, 0);

        $ci = Interval::week(3);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 0, 0, 21, 0, 0, 0);
    }

    public function testDays()
    {
        $ci = new Interval(0, 0, 0, 1);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 0, 0, 1, 0, 0, 0);

        $ci = Interval::days(2);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 0, 0, 2, 0, 0, 0);

        $ci = Interval::dayz(2);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 0, 0, 2, 0, 0, 0);

        $ci = Interval::day();
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 0, 0, 1, 0, 0, 0);

        $ci = Interval::day(3);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 0, 0, 3, 0, 0, 0);
    }

    public function testHours()
    {
        $ci = new Interval(0, 0, 0, 0, 1);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 0, 0, 0, 1, 0, 0);

        $ci = Interval::hours(2);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 0, 0, 0, 2, 0, 0);

        $ci = Interval::hour();
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 0, 0, 0, 1, 0, 0);

        $ci = Interval::hour(3);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 0, 0, 0, 3, 0, 0);
    }

    public function testMinutes()
    {
        $ci = new Interval(0, 0, 0, 0, 0, 1);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 0, 0, 0, 0, 1, 0);

        $ci = Interval::minutes(2);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 0, 0, 0, 0, 2, 0);

        $ci = Interval::minute();
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 0, 0, 0, 0, 1, 0);

        $ci = Interval::minute(3);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 0, 0, 0, 0, 3, 0);
    }

    public function testSeconds()
    {
        $ci = new Interval(0, 0, 0, 0, 0, 0, 1);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 0, 0, 0, 0, 0, 1);

        $ci = Interval::seconds(2);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 0, 0, 0, 0, 0, 2);

        $ci = Interval::second();
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 0, 0, 0, 0, 0, 1);

        $ci = Interval::second(3);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 0, 0, 0, 0, 0, 3);
    }

    public function testYearsAndHours()
    {
        $ci = new Interval(5, 0, 0, 0, 3, 0, 0);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 5, 0, 0, 3, 0, 0);
    }

    public function testAll()
    {
        $ci = new Interval(5, 6, 2, 5, 9, 10, 11);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 5, 6, 19, 9, 10, 11);
    }

    public function testAllWithCreate()
    {
        $ci = Interval::create(5, 6, 2, 5, 9, 10, 11);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 5, 6, 19, 9, 10, 11);
    }

    public function testInstance()
    {
        $interval = new DateInterval('P2Y1M5DT22H33M44S');
        $ci = Interval::instance($interval);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 2, 1, 5, 22, 33, 44);
        $this->assertTrue($ci->days === false || $ci->days === Interval::PHP_DAYS_FALSE);
    }

    public function testInstanceWithNegativeInterval()
    {
        $di = new DateInterval('P2Y1M5DT22H33M44S');
        $di->invert = 1;
        $ci = Interval::instance($di);
        $this->assertInstanceOfInterval($ci);
        $this->assertInterval($ci, 2, 1, 5, 22, 33, 44);
        $this->assertTrue($ci->days === false || $ci->days === Interval::PHP_DAYS_FALSE);
        $this->assertSame(1, $ci->invert);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInstanceWithDaysThrowsException()
    {
        Interval::instance(Date::now()->diff(Date::now()->addWeeks(3)));
    }
}
