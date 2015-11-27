<?php

namespace HMLB\Date\Tests\Interval;

/*
 * This file is part of the Date package.
 *
 * (c) Hugues Maignol <hugues@hmlb.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use HMLB\Date\Interval;
use HMLB\Date\Tests\AbstractTestCase;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Translator;

class ForHumansTest extends AbstractTestCase
{
    public function testGetTranslator()
    {
        $t = Interval::getTranslator();
        $this->assertNotNull($t);
        $this->assertSame('en', $t->getLocale());
    }

    public function testSetTranslator()
    {
        $t = new Translator('fr');
        $t->addLoader('array', new ArrayLoader());
        Interval::setTranslator($t);

        $t = Interval::getTranslator();
        $this->assertNotNull($t);
        $this->assertSame('fr', $t->getLocale());
    }

    public function testGetLocale()
    {
        Interval::setLocale('en');
        $this->assertSame('en', Interval::getLocale());
    }

    public function testSetLocale()
    {
        Interval::setLocale('en');
        $this->assertSame('en', Interval::getLocale());
        Interval::setLocale('fr');
        $this->assertSame('fr', Interval::getLocale());
    }

    public function testYear()
    {
        Interval::setLocale('en');
        $this->assertSame('1 year', Interval::year()->forHumans());
    }

    public function testYearToString()
    {
        Interval::setLocale('en');
        $this->assertSame('1 year:abc', Interval::year().':abc');
    }

    public function testYears()
    {
        Interval::setLocale('en');
        $this->assertSame('2 years', Interval::years(2)->forHumans());
    }

    public function testYearsAndMonth()
    {
        Interval::setLocale('en');
        $this->assertSame('2 years 1 month', Interval::create(2, 1)->forHumans());
    }

    public function testAll()
    {
        Interval::setLocale('en');
        $ci = Interval::create(11, 1, 2, 5, 22, 33, 55)->forHumans();
        $this->assertSame('11 years 1 month 2 weeks 5 days 22 hours 33 minutes 55 seconds', $ci);
    }

    public function testYearsAndMonthInFrench()
    {
        Interval::setLocale('fr');
        $this->assertSame('2 ans 1 mois', Interval::create(2, 1)->forHumans());
    }

    public function testYearsAndMonthInGerman()
    {
        Interval::setLocale('de');
        $this->assertSame('1 Jahr 1 Monat', Interval::create(1, 1)->forHumans());
        $this->assertSame('2 Jahre 1 Monat', Interval::create(2, 1)->forHumans());
    }

    public function testYearsAndMonthInBulgarian()
    {
        Interval::setLocale('bg');
        $this->assertSame('1 година 1 месец', Interval::create(1, 1)->forHumans());
        $this->assertSame('2 години 1 месец', Interval::create(2, 1)->forHumans());
    }

    public function testYearsAndMonthInCatalan()
    {
        Interval::setLocale('ca');
        $this->assertSame('1 any 1 mes', Interval::create(1, 1)->forHumans());
        $this->assertSame('2 anys 1 mes', Interval::create(2, 1)->forHumans());
    }

    public function testYearsAndMonthInCzech()
    {
        Interval::setLocale('cs');
        $this->assertSame('1 rok 1 měsíc', Interval::create(1, 1)->forHumans());
        $this->assertSame('2 roky 1 měsíc', Interval::create(2, 1)->forHumans());
    }

    public function testYearsAndMonthInGreek()
    {
        Interval::setLocale('el');
        $this->assertSame('1 χρόνος 1 μήνας', Interval::create(1, 1)->forHumans());
        $this->assertSame('2 χρόνια 1 μήνας', Interval::create(2, 1)->forHumans());
    }

    public function testYearsAndMonthsInDanish()
    {
        Interval::setLocale('da');
        $this->assertSame('1 år 1 måned', Interval::create(1, 1)->forHumans());
        $this->assertSame('2 år 1 måned', Interval::create(2, 1)->forHumans());
    }
}
