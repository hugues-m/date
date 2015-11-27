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

class DaTest extends AbstractTestCase
{
    public function testDiffForHumansLocalizedInDanish()
    {
        Date::setLocale('da');

        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $d = Date::now()->subSecond();
                $scope->assertSame('1 sekund siden', $d->diffForHumans());

                $d = Date::now()->subSeconds(2);
                $scope->assertSame('2 sekunder siden', $d->diffForHumans());

                $d = Date::now()->subMinute();
                $scope->assertSame('1 minut siden', $d->diffForHumans());

                $d = Date::now()->subMinutes(2);
                $scope->assertSame('2 minutter siden', $d->diffForHumans());

                $d = Date::now()->subHour();
                $scope->assertSame('1 time siden', $d->diffForHumans());

                $d = Date::now()->subHours(2);
                $scope->assertSame('2 timer siden', $d->diffForHumans());

                $d = Date::now()->subDay();
                $scope->assertSame('1 dag siden', $d->diffForHumans());

                $d = Date::now()->subDays(2);
                $scope->assertSame('2 dage siden', $d->diffForHumans());

                $d = Date::now()->subWeek();
                $scope->assertSame('1 uge siden', $d->diffForHumans());

                $d = Date::now()->subWeeks(2);
                $scope->assertSame('2 uger siden', $d->diffForHumans());

                $d = Date::now()->subMonth();
                $scope->assertSame('1 måned siden', $d->diffForHumans());

                $d = Date::now()->subMonths(2);
                $scope->assertSame('2 måneder siden', $d->diffForHumans());

                $d = Date::now()->subYear();
                $scope->assertSame('1 år siden', $d->diffForHumans());

                $d = Date::now()->subYears(2);
                $scope->assertSame('2 år siden', $d->diffForHumans());

                $d = Date::now()->addSecond();
                $scope->assertSame('om 1 sekund', $d->diffForHumans());

                $d = Date::now()->addSecond();
                $d2 = Date::now();
                $scope->assertSame('1 sekund efter', $d->diffForHumans($d2));
                $scope->assertSame('1 sekund før', $d2->diffForHumans($d));

                $scope->assertSame('1 sekund', $d->diffForHumans($d2, true));
                $scope->assertSame('2 sekunder', $d2->diffForHumans($d->addSecond(), true));
            }
        );
    }
}
