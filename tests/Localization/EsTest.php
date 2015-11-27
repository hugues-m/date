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

class EsTest extends AbstractTestCase
{
    public function testDiffForHumansLocalizedInSpanish()
    {
        Date::setLocale('es');

        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $d = Date::now()->subSecond();
                $scope->assertSame('hace 1 segundo', $d->diffForHumans());

                $d = Date::now()->subSeconds(3);
                $scope->assertSame('hace 3 segundos', $d->diffForHumans());

                $d = Date::now()->subMinute();
                $scope->assertSame('hace 1 minuto', $d->diffForHumans());

                $d = Date::now()->subMinutes(2);
                $scope->assertSame('hace 2 minutos', $d->diffForHumans());

                $d = Date::now()->subHour();
                $scope->assertSame('hace 1 hora', $d->diffForHumans());

                $d = Date::now()->subHours(2);
                $scope->assertSame('hace 2 horas', $d->diffForHumans());

                $d = Date::now()->subDay();
                $scope->assertSame('hace 1 día', $d->diffForHumans());

                $d = Date::now()->subDays(2);
                $scope->assertSame('hace 2 días', $d->diffForHumans());

                $d = Date::now()->subWeek();
                $scope->assertSame('hace 1 semana', $d->diffForHumans());

                $d = Date::now()->subWeeks(2);
                $scope->assertSame('hace 2 semanas', $d->diffForHumans());

                $d = Date::now()->subMonth();
                $scope->assertSame('hace 1 mes', $d->diffForHumans());

                $d = Date::now()->subMonths(2);
                $scope->assertSame('hace 2 meses', $d->diffForHumans());

                $d = Date::now()->subYear();
                $scope->assertSame('hace 1 año', $d->diffForHumans());

                $d = Date::now()->subYears(2);
                $scope->assertSame('hace 2 años', $d->diffForHumans());

                $d = Date::now()->addSecond();
                $scope->assertSame('dentro de 1 segundo', $d->diffForHumans());

                $d = Date::now()->addSecond();
                $d2 = Date::now();
                $scope->assertSame('1 segundo después', $d->diffForHumans($d2));
                $scope->assertSame('1 segundo antes', $d2->diffForHumans($d));

                $scope->assertSame('1 segundo', $d->diffForHumans($d2, true));
                $scope->assertSame('2 segundos', $d2->diffForHumans($d->addSecond(), true));
            }
        );
    }
}
