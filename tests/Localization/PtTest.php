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

class PtTest extends AbstractTestCase
{
    public function testDiffForHumansLocalizedInPortuguese()
    {
        Date::setLocale('pt');

        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $d = Date::now()->subSecond();
                $scope->assertSame('1 segundo atrás', $d->diffForHumans());
            }
        );
    }

    public function testDiffForHumansLocalizedInPortugueseBrazil()
    {
        Date::setLocale('pt-BR');

        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $d = Date::now()->subSecond();
                $scope->assertSame('há 1 segundo', $d->diffForHumans());
            }
        );
    }

    public function testDiffForHumansLocalizedInPortugueseBrazilBC()
    {
        Date::setLocale('pt_BR');

        $scope = $this;
        $this->wrapWithTestNow(
            function () use ($scope) {
                $d = Date::now()->subSecond();
                $scope->assertSame('há 1 segundo', $d->diffForHumans());
            }
        );
    }
}
