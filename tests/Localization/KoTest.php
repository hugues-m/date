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

class KoTest extends AbstractTestCase
{
    public function testDiffForHumansLocalizedInKorean()
    {
        Date::setLocale('ko');

        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $d = Date::now()->addYear();
                $scope->assertSame('1 년 후', $d->diffForHumans());

                $d = Date::now()->addYears(2);
                $scope->assertSame('2 년 후', $d->diffForHumans());
            }
        );
    }
}
