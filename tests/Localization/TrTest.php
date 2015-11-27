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

class TrTest extends AbstractTestCase
{
    public function testDiffForHumansLocalizedInTurkish()
    {
        Date::setLocale('tr');

        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $d = Date::now()->subSecond();
                $scope->assertSame('1 saniye önce', $d->diffForHumans());

                $d = Date::now()->subSeconds(2);
                $scope->assertSame('2 saniye önce', $d->diffForHumans());

                $d = Date::now()->subMinute();
                $scope->assertSame('1 dakika önce', $d->diffForHumans());

                $d = Date::now()->subMinutes(2);
                $scope->assertSame('2 dakika önce', $d->diffForHumans());

                $d = Date::now()->subHour();
                $scope->assertSame('1 saat önce', $d->diffForHumans());

                $d = Date::now()->subHours(2);
                $scope->assertSame('2 saat önce', $d->diffForHumans());

                $d = Date::now()->subDay();
                $scope->assertSame('1 gün önce', $d->diffForHumans());

                $d = Date::now()->subDays(2);
                $scope->assertSame('2 gün önce', $d->diffForHumans());

                $d = Date::now()->subWeek();
                $scope->assertSame('1 hafta önce', $d->diffForHumans());

                $d = Date::now()->subWeeks(2);
                $scope->assertSame('2 hafta önce', $d->diffForHumans());

                $d = Date::now()->subMonth();
                $scope->assertSame('1 ay önce', $d->diffForHumans());

                $d = Date::now()->subMonths(2);
                $scope->assertSame('2 ay önce', $d->diffForHumans());

                $d = Date::now()->subYear();
                $scope->assertSame('1 yıl önce', $d->diffForHumans());

                $d = Date::now()->subYears(2);
                $scope->assertSame('2 yıl önce', $d->diffForHumans());

                $d = Date::now()->addSecond();
                $scope->assertSame('1 saniye andan itibaren', $d->diffForHumans());

                $d = Date::now()->addSecond();
                $d2 = Date::now();
                $scope->assertSame('1 saniye sonra', $d->diffForHumans($d2));
                $scope->assertSame('1 saniye önce', $d2->diffForHumans($d));

                $scope->assertSame('1 saniye', $d->diffForHumans($d2, true));
                $scope->assertSame('2 saniye', $d2->diffForHumans($d->addSecond(), true));
            }
        );
    }
}
