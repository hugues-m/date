<?php

namespace HMLB\Date\Tests\Localization;

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

class LtTest extends AbstractTestCase
{
    public function testDiffForHumansLocalizedInLithuanian()
    {
        Date::setLocale('lt');

        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $d = Date::now()->addYear();
                $scope->assertSame('už 1 metus', $d->diffForHumans());

                $d = Date::now()->addYears(2);
                $scope->assertSame('už 2 metus', $d->diffForHumans());
            }
        );
    }
}
