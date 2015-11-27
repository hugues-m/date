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

class ComparisonTest extends AbstractTestCase
{
    public function testEqualToTrue()
    {
        $this->assertTrue(Date::createFromDate(2000, 1, 1)->equals(Date::createFromDate(2000, 1, 1)));
    }

    public function testEqualToFalse()
    {
        $this->assertFalse(Date::createFromDate(2000, 1, 1)->equals(Date::createFromDate(2000, 1, 2)));
    }

    public function testEqualWithTimezoneTrue()
    {
        $this->assertTrue(
            Date::create(2000, 1, 1, 12, 0, 0, 'America/Toronto')->equals(
                Date::create(2000, 1, 1, 9, 0, 0, 'America/Vancouver')
            )
        );
    }

    public function testEqualWithTimezoneFalse()
    {
        $this->assertFalse(
            Date::createFromDate(2000, 1, 1, 'America/Toronto')->equals(
                Date::createFromDate(2000, 1, 1, 'America/Vancouver')
            )
        );
    }

    public function testNotEqualToTrue()
    {
        $this->assertTrue(Date::createFromDate(2000, 1, 1)->notEquals(Date::createFromDate(2000, 1, 2)));
    }

    public function testNotEqualToFalse()
    {
        $this->assertFalse(Date::createFromDate(2000, 1, 1)->notEquals(Date::createFromDate(2000, 1, 1)));
    }

    public function testNotEqualWithTimezone()
    {
        $this->assertTrue(
            Date::createFromDate(2000, 1, 1, 'America/Toronto')->notEquals(
                Date::createFromDate(2000, 1, 1, 'America/Vancouver')
            )
        );
    }

    public function testGreaterThanTrue()
    {
        $this->assertTrue(Date::createFromDate(2000, 1, 1)->isAfter(Date::createFromDate(1999, 12, 31)));
    }

    public function testGreaterThanFalse()
    {
        $this->assertFalse(Date::createFromDate(2000, 1, 1)->isAfter(Date::createFromDate(2000, 1, 2)));
    }

    public function testGreaterThanWithTimezoneTrue()
    {
        $dt1 = Date::create(2000, 1, 1, 12, 0, 0, 'America/Toronto');
        $dt2 = Date::create(2000, 1, 1, 8, 59, 59, 'America/Vancouver');
        $this->assertTrue($dt1->isAfter($dt2));
    }

    public function testGreaterThanWithTimezoneFalse()
    {
        $dt1 = Date::create(2000, 1, 1, 12, 0, 0, 'America/Toronto');
        $dt2 = Date::create(2000, 1, 1, 9, 0, 1, 'America/Vancouver');
        $this->assertFalse($dt1->isAfter($dt2));
    }

    public function testGreaterThanOrEqualTrue()
    {
        $this->assertTrue(Date::createFromDate(2000, 1, 1)->isAfterOrEquals(Date::createFromDate(1999, 12, 31)));
    }

    public function testGreaterThanOrEqualTrueEqual()
    {
        $this->assertTrue(Date::createFromDate(2000, 1, 1)->isAfterOrEquals(Date::createFromDate(2000, 1, 1)));
    }

    public function testGreaterThanOrEqualFalse()
    {
        $this->assertFalse(Date::createFromDate(2000, 1, 1)->isAfterOrEquals(Date::createFromDate(2000, 1, 2)));
    }

    public function testLessThanTrue()
    {
        $this->assertTrue(Date::createFromDate(2000, 1, 1)->isBefore(Date::createFromDate(2000, 1, 2)));
    }

    public function testLessThanFalse()
    {
        $this->assertFalse(Date::createFromDate(2000, 1, 1)->isBefore(Date::createFromDate(1999, 12, 31)));
    }

    public function testLessThanOrEqualTrue()
    {
        $this->assertTrue(Date::createFromDate(2000, 1, 1)->isBeforeOrEquals(Date::createFromDate(2000, 1, 2)));
    }

    public function testLessThanOrEqualTrueEqual()
    {
        $this->assertTrue(Date::createFromDate(2000, 1, 1)->isBeforeOrEquals(Date::createFromDate(2000, 1, 1)));
    }

    public function testLessThanOrEqualFalse()
    {
        $this->assertFalse(Date::createFromDate(2000, 1, 1)->isBeforeOrEquals(Date::createFromDate(1999, 12, 31)));
    }

    public function testBetweenEqualTrue()
    {
        $this->assertTrue(
            Date::createFromDate(2000, 1, 15)->isBetween(
                Date::createFromDate(2000, 1, 1),
                Date::createFromDate(2000, 1, 31),
                true
            )
        );
    }

    public function testBetweenNotEqualTrue()
    {
        $this->assertTrue(
            Date::createFromDate(2000, 1, 15)->isBetween(
                Date::createFromDate(2000, 1, 1),
                Date::createFromDate(2000, 1, 31),
                false
            )
        );
    }

    public function testBetweenEqualFalse()
    {
        $this->assertFalse(
            Date::createFromDate(1999, 12, 31)->isBetween(
                Date::createFromDate(2000, 1, 1),
                Date::createFromDate(2000, 1, 31),
                true
            )
        );
    }

    public function testBetweenNotEqualFalse()
    {
        $this->assertFalse(
            Date::createFromDate(2000, 1, 1)->isBetween(
                Date::createFromDate(2000, 1, 1),
                Date::createFromDate(2000, 1, 31),
                false
            )
        );
    }

    public function testBetweenEqualSwitchTrue()
    {
        $this->assertTrue(
            Date::createFromDate(2000, 1, 15)->isBetween(
                Date::createFromDate(2000, 1, 31),
                Date::createFromDate(2000, 1, 1),
                true
            )
        );
    }

    public function testBetweenNotEqualSwitchTrue()
    {
        $this->assertTrue(
            Date::createFromDate(2000, 1, 15)->isBetween(
                Date::createFromDate(2000, 1, 31),
                Date::createFromDate(2000, 1, 1),
                false
            )
        );
    }

    public function testBetweenEqualSwitchFalse()
    {
        $this->assertFalse(
            Date::createFromDate(1999, 12, 31)->isBetween(
                Date::createFromDate(2000, 1, 31),
                Date::createFromDate(2000, 1, 1),
                true
            )
        );
    }

    public function testBetweenNotEqualSwitchFalse()
    {
        $this->assertFalse(
            Date::createFromDate(2000, 1, 1)->isBetween(
                Date::createFromDate(2000, 1, 31),
                Date::createFromDate(2000, 1, 1),
                false
            )
        );
    }

    public function testMinIsFluid()
    {
        $dt = Date::now();
        $this->assertTrue($dt->min() instanceof Date);
    }

    public function testMinWithNow()
    {
        $dt = Date::create(2012, 1, 1, 0, 0, 0)->min();
        $this->assertDate($dt, 2012, 1, 1, 0, 0, 0);
    }

    public function testMinWithInstance()
    {
        $dt1 = Date::create(2013, 12, 31, 23, 59, 59);
        $dt2 = Date::create(2012, 1, 1, 0, 0, 0)->min($dt1);
        $this->assertDate($dt2, 2012, 1, 1, 0, 0, 0);
    }

    public function testMaxIsFluid()
    {
        $dt = Date::now();
        $this->assertTrue($dt->max() instanceof Date);
    }

    public function testMaxWithNow()
    {
        $dt = Date::create(2099, 12, 31, 23, 59, 59)->max();
        $this->assertDate($dt, 2099, 12, 31, 23, 59, 59);
    }

    public function testMaxWithInstance()
    {
        $dt1 = Date::create(2012, 1, 1, 0, 0, 0);
        $dt2 = Date::create(2099, 12, 31, 23, 59, 59)->max($dt1);
        $this->assertDate($dt2, 2099, 12, 31, 23, 59, 59);
    }

    public function testIsBirthday()
    {
        $dt = Date::now();
        $aBirthday = $dt->subYear();
        $this->assertTrue($aBirthday->isBirthday());
        $notABirthday = $dt->subDay();
        $this->assertFalse($notABirthday->isBirthday());
        $alsoNotABirthday = $dt->addDays(2);
        $this->assertFalse($alsoNotABirthday->isBirthday());

        $dt1 = Date::createFromDate(1987, 4, 23);
        $dt2 = Date::createFromDate(2014, 9, 26);
        $dt3 = Date::createFromDate(2014, 4, 23);
        $this->assertFalse($dt2->isBirthday($dt1));
        $this->assertTrue($dt3->isBirthday($dt1));
    }

    public function testClosest()
    {
        $instance = Date::create(2015, 5, 28, 12, 0, 0);
        $dt1 = Date::create(2015, 5, 28, 11, 0, 0);
        $dt2 = Date::create(2015, 5, 28, 14, 0, 0);
        $closest = $instance->closest($dt1, $dt2);
        $this->assertEquals($dt1, $closest);
    }

    public function testClosestWithEquals()
    {
        $instance = Date::create(2015, 5, 28, 12, 0, 0);
        $dt1 = Date::create(2015, 5, 28, 12, 0, 0);
        $dt2 = Date::create(2015, 5, 28, 14, 0, 0);
        $closest = $instance->closest($dt1, $dt2);
        $this->assertEquals($dt1, $closest);
    }

    public function testFarthest()
    {
        $instance = Date::create(2015, 5, 28, 12, 0, 0);
        $dt1 = Date::create(2015, 5, 28, 11, 0, 0);
        $dt2 = Date::create(2015, 5, 28, 14, 0, 0);
        $Farthest = $instance->farthest($dt1, $dt2);
        $this->assertEquals($dt2, $Farthest);
    }

    public function testFarthestWithEquals()
    {
        $instance = Date::create(2015, 5, 28, 12, 0, 0);
        $dt1 = Date::create(2015, 5, 28, 12, 0, 0);
        $dt2 = Date::create(2015, 5, 28, 14, 0, 0);
        $Farthest = $instance->farthest($dt1, $dt2);
        $this->assertEquals($dt2, $Farthest);
    }
}
