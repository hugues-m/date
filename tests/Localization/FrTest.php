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

class FrTest extends AbstractTestCase
{
    public function testDiffForHumansLocalizedInFrench()
    {
        Date::setLocale('fr');

        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {

                $d = Date::now()->subSecond();
                $scope->assertSame('il y a 1 seconde', $d->diffForHumans());

                $d = Date::now()->subSeconds(2);
                $scope->assertSame('il y a 2 secondes', $d->diffForHumans());

                $d = Date::now()->subMinute();
                $scope->assertSame('il y a 1 minute', $d->diffForHumans());

                $d = Date::now()->subMinutes(2);
                $scope->assertSame('il y a 2 minutes', $d->diffForHumans());

                $d = Date::now()->subHour();
                $scope->assertSame('il y a 1 heure', $d->diffForHumans());

                $d = Date::now()->subHours(2);
                $scope->assertSame('il y a 2 heures', $d->diffForHumans());

                $d = Date::now()->subDay();
                $scope->assertSame('il y a 1 jour', $d->diffForHumans());

                $d = Date::now()->subDays(2);
                $scope->assertSame('il y a 2 jours', $d->diffForHumans());

                $d = Date::now()->subWeek();
                $scope->assertSame('il y a 1 semaine', $d->diffForHumans());

                $d = Date::now()->subWeeks(2);
                $scope->assertSame('il y a 2 semaines', $d->diffForHumans());

                $d = Date::now()->subMonth();
                $scope->assertSame('il y a 1 mois', $d->diffForHumans());

                $d = Date::now()->subMonths(2);
                $scope->assertSame('il y a 2 mois', $d->diffForHumans());

                $d = Date::now()->subYear();
                $scope->assertSame('il y a 1 an', $d->diffForHumans());

                $d = Date::now()->subYears(2);
                $scope->assertSame('il y a 2 ans', $d->diffForHumans());

                $d = Date::now()->addSecond();
                $scope->assertSame('dans 1 seconde', $d->diffForHumans());

                $d = Date::now()->addSecond();
                $d2 = Date::now();
                $scope->assertSame('1 seconde après', $d->diffForHumans($d2));
                $scope->assertSame('1 seconde avant', $d2->diffForHumans($d));

                $scope->assertSame('1 seconde', $d->diffForHumans($d2, true));
                $scope->assertSame('2 secondes', $d2->diffForHumans($d->addSecond(), true));
            }
        );
    }
}
