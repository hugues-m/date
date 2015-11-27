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

class FaTest extends AbstractTestCase
{
    public function testDiffForHumansLocalizedInFarsi()
    {
        Date::setLocale('fa');

        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $d = Date::now()->subSecond();
                $scope->assertSame('1 ثانیه پیش', $d->diffForHumans());

                $d = Date::now()->subSeconds(2);
                $scope->assertSame('2 ثانیه پیش', $d->diffForHumans());

                $d = Date::now()->subMinute();
                $scope->assertSame('1 دقیقه پیش', $d->diffForHumans());

                $d = Date::now()->subMinutes(2);
                $scope->assertSame('2 دقیقه پیش', $d->diffForHumans());

                $d = Date::now()->subHour();
                $scope->assertSame('1 ساعت پیش', $d->diffForHumans());

                $d = Date::now()->subHours(2);
                $scope->assertSame('2 ساعت پیش', $d->diffForHumans());

                $d = Date::now()->subDay();
                $scope->assertSame('1 روز پیش', $d->diffForHumans());

                $d = Date::now()->subDays(2);
                $scope->assertSame('2 روز پیش', $d->diffForHumans());

                $d = Date::now()->subWeek();
                $scope->assertSame('1 هفته پیش', $d->diffForHumans());

                $d = Date::now()->subWeeks(2);
                $scope->assertSame('2 هفته پیش', $d->diffForHumans());

                $d = Date::now()->subMonth();
                $scope->assertSame('1 ماه پیش', $d->diffForHumans());

                $d = Date::now()->subMonths(2);
                $scope->assertSame('2 ماه پیش', $d->diffForHumans());

                $d = Date::now()->subYear();
                $scope->assertSame('1 سال پیش', $d->diffForHumans());

                $d = Date::now()->subYears(2);
                $scope->assertSame('2 سال پیش', $d->diffForHumans());

                $d = Date::now()->addSecond();
                $scope->assertSame('1 ثانیه بعد', $d->diffForHumans());

                $d = Date::now()->addSecond();
                $d2 = Date::now();
                $scope->assertSame('1 ثانیه پیش از', $d->diffForHumans($d2));
                $scope->assertSame('1 ثانیه پس از', $d2->diffForHumans($d));

                $d = Date::now()->addSecond();
                $d2 = Date::now();
                $scope->assertSame('1 ثانیه پیش از', $d->diffForHumans($d2));
                $scope->assertSame('1 ثانیه پس از', $d2->diffForHumans($d));

                $scope->assertSame('1 ثانیه', $d->diffForHumans($d2, true));
                $scope->assertSame('2 ثانیه', $d2->diffForHumans($d->addSecond(), true));
            }
        );
    }
}
