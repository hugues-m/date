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

class InstanceTest extends AbstractTestCase
{
    public function testInstanceFromDateTime()
    {
        $dating = Date::instance(\DateTime::createFromFormat('Y-m-d H:i:s', '1975-05-21 22:32:11'));
        $this->assertDate($dating, 1975, 5, 21, 22, 32, 11);
    }

    public function testInstanceFromDateTimeKeepsTimezoneName()
    {
        $dating = Date::instance(
            \DateTime::createFromFormat('Y-m-d H:i:s', '1975-05-21 22:32:11')->setTimezone(
                new \DateTimeZone('America/Vancouver')
            )
        );
        $this->assertSame('America/Vancouver', $dating->getTimezoneName());
    }

    public function testInstanceFromDateTimeKeepsMicros()
    {
        $micro = 254687;
        $datetime = \DateTime::createFromFormat('Y-m-d H:i:s.u', '2014-02-01 03:45:27.'.$micro);
        $date = Date::instance($datetime);
        $this->assertSame($micro, $date->getMicro());
    }
}
