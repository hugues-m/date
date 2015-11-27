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

class FoTest extends AbstractTestCase
{
    public function testDiffForHumansLocalizedInFaroese()
    {
        Date::setLocale('fo');

        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $d = Date::now()->subSecond();
                $scope->assertSame('1 sekund síðan', $d->diffForHumans());

                $d = Date::now()->subSeconds(2);
                $scope->assertSame('2 sekundir síðan', $d->diffForHumans());

                $d = Date::now()->subMinute();
                $scope->assertSame('1 minutt síðan', $d->diffForHumans());

                $d = Date::now()->subMinutes(2);
                $scope->assertSame('2 minuttir síðan', $d->diffForHumans());

                $d = Date::now()->subHour();
                $scope->assertSame('1 tími síðan', $d->diffForHumans());

                $d = Date::now()->subHours(2);
                $scope->assertSame('2 tímar síðan', $d->diffForHumans());

                $d = Date::now()->subDay();
                $scope->assertSame('1 dag síðan', $d->diffForHumans());

                $d = Date::now()->subDays(2);
                $scope->assertSame('2 dagar síðan', $d->diffForHumans());

                $d = Date::now()->subWeek();
                $scope->assertSame('1 vika síðan', $d->diffForHumans());

                $d = Date::now()->subWeeks(2);
                $scope->assertSame('2 vikur síðan', $d->diffForHumans());

                $d = Date::now()->subMonth();
                $scope->assertSame('1 mánaður síðan', $d->diffForHumans());

                $d = Date::now()->subMonths(2);
                $scope->assertSame('2 mánaðir síðan', $d->diffForHumans());

                $d = Date::now()->subYear();
                $scope->assertSame('1 ár síðan', $d->diffForHumans());

                $d = Date::now()->subYears(2);
                $scope->assertSame('2 ár síðan', $d->diffForHumans());

                $d = Date::now()->addSecond();
                $scope->assertSame('um 1 sekund', $d->diffForHumans());

                $d = Date::now()->addSecond();
                $d2 = Date::now();
                $scope->assertSame('1 sekund aftaná', $d->diffForHumans($d2));
                $scope->assertSame('1 sekund áðrenn', $d2->diffForHumans($d));

                $scope->assertSame('1 sekund', $d->diffForHumans($d2, true));
                $scope->assertSame('2 sekundir', $d2->diffForHumans($d->addSecond(), true));
            }
        );
    }
}
