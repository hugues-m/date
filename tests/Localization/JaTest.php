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

class JaTest extends AbstractTestCase
{
    public function testDiffForHumansLocalizedInJapanese()
    {
        Date::setLocale('ja');

        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $d = Date::now()->subSecond();
                $scope->assertSame('1 秒 前', $d->diffForHumans());

                $d = Date::now()->subSeconds(2);
                $scope->assertSame('2 秒 前', $d->diffForHumans());

                $d = Date::now()->subMinute();
                $scope->assertSame('1 分 前', $d->diffForHumans());

                $d = Date::now()->subMinutes(2);
                $scope->assertSame('2 分 前', $d->diffForHumans());

                $d = Date::now()->subHour();
                $scope->assertSame('1 時間 前', $d->diffForHumans());

                $d = Date::now()->subHours(2);
                $scope->assertSame('2 時間 前', $d->diffForHumans());

                $d = Date::now()->subDay();
                $scope->assertSame('1 日 前', $d->diffForHumans());

                $d = Date::now()->subDays(2);
                $scope->assertSame('2 日 前', $d->diffForHumans());

                $d = Date::now()->subWeek();
                $scope->assertSame('1 週間 前', $d->diffForHumans());

                $d = Date::now()->subWeeks(2);
                $scope->assertSame('2 週間 前', $d->diffForHumans());

                $d = Date::now()->subMonth();
                $scope->assertSame('1 ヶ月 前', $d->diffForHumans());

                $d = Date::now()->subMonths(2);
                $scope->assertSame('2 ヶ月 前', $d->diffForHumans());

                $d = Date::now()->subYear();
                $scope->assertSame('1 年 前', $d->diffForHumans());

                $d = Date::now()->subYears(2);
                $scope->assertSame('2 年 前', $d->diffForHumans());

                $d = Date::now()->addSecond();
                $scope->assertSame('今から 1 秒', $d->diffForHumans());

                $d = Date::now()->addSecond();
                $d2 = Date::now();
                $scope->assertSame('1 秒 後', $d->diffForHumans($d2));
                $scope->assertSame('1 秒 前', $d2->diffForHumans($d));

                $scope->assertSame('1 秒', $d->diffForHumans($d2, true));
                $scope->assertSame('2 秒', $d2->diffForHumans($d->addSecond(), true));
            }
        );
    }
}
