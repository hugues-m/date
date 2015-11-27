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

class DeTest extends AbstractTestCase
{
    public function testDiffForHumansLocalizedInGerman()
    {
        Date::setLocale('de');

        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $d = Date::now()->addYear();
                $scope->assertSame('in 1 Jahr', $d->diffForHumans());

                $d = Date::now()->addYears(2);
                $scope->assertSame('in 2 Jahren', $d->diffForHumans());

                $d = Date::now()->subYear();
                $scope->assertSame('1 Jahr später', Date::now()->diffForHumans($d));

                $d = Date::now()->subYears(2);
                $scope->assertSame('2 Jahre später', Date::now()->diffForHumans($d));

                $d = Date::now()->addYear();
                $scope->assertSame('1 Jahr zuvor', Date::now()->diffForHumans($d));

                $d = Date::now()->addYears(2);
                $scope->assertSame('2 Jahre zuvor', Date::now()->diffForHumans($d));

                $d = Date::now()->subYear();
                $scope->assertSame('vor 1 Jahr', $d->diffForHumans());

                $d = Date::now()->subYears(2);
                $scope->assertSame('vor 2 Jahren', $d->diffForHumans());
            }
        );
    }
}
