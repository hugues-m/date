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

class CopyTest extends AbstractTestCase
{
    public function testCopy()
    {
        $dating = Date::now();
        $dating2 = $dating->copy();
        $this->assertNotSame($dating, $dating2);
    }

    public function testCopyEnsureTzIsCopied()
    {
        $dating = Date::createFromDate(2000, 1, 1, 'Europe/London');
        $dating2 = $dating->copy();
        $this->assertSame($dating->getTimezoneName(), $dating2->getTimezoneName());
        $this->assertSame($dating->getOffset(), $dating2->getOffset());
    }

    public function testCopyEnsureMicrosAreCopied()
    {
        $micro = 254687;
        $dating = Date::createFromFormat('Y-m-d H:i:s.u', '2014-02-01 03:45:27.'.$micro);
        $dating2 = $dating->copy();
        $this->assertSame($micro, $dating2->getMicro());
    }
}
