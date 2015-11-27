<?php

namespace HMLB\Date\Tests\Date;

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
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Translator;

class LocalizationTest extends AbstractTestCase
{
    public function testGetTranslator()
    {
        $t = Date::getTranslator();
        $this->assertNotNull($t);
        $this->assertSame('en', $t->getLocale());
    }

    /**
     * @see testSetLocale
     * @see testSetTranslator
     *
     * @return array
     */
    public function providerLocales()
    {
        return [
            ['af'],
            ['ar'],
            ['az'],
            ['bg'],
            ['bn'],
            ['ca'],
            ['cs'],
            ['da'],
            ['de'],
            ['el'],
            ['en'],
            ['eo'],
            ['es'],
            ['et'],
            ['eu'],
            ['fa'],
            ['fi'],
            ['fo'],
            ['fr'],
            ['he'],
            ['hr'],
            ['hu'],
            ['id'],
            ['it'],
            ['ja'],
            ['ko'],
            ['lt'],
            ['lv'],
            ['ms'],
            ['nl'],
            ['no'],
            ['pl'],
            ['pt'],
            ['pt_BR'],
            ['pt-BR'],
            ['ro'],
            ['ru'],
            ['sk'],
            ['sl'],
            ['sq'],
            ['sr'],
            ['sv'],
            ['th'],
            ['tr'],
            ['uk'],
            ['uz'],
            ['vi'],
            ['zh'],
            ['zh-TW'],
        ];
    }

    /**
     * @dataProvider providerLocales
     *
     * @param string $locale
     */
    public function testSetLocale($locale)
    {
        $this->assertTrue(Date::setLocale($locale));
        $this->assertSame($locale, Date::getLocale());
    }

    /**
     * @dataProvider providerLocales
     *
     * @param string $locale
     */
    public function testSetTranslator($locale)
    {
        $t = new Translator($locale);
        $t->addLoader('array', new ArrayLoader());
        Date::setTranslator($t);

        $t = Date::getTranslator();
        $this->assertNotNull($t);
        $this->assertSame($locale, $t->getLocale());
    }

    public function testSetLocaleWithKnownLocale()
    {
        $this->assertTrue(Date::setLocale('fr'));
    }

    public function testSetLocaleWithUnknownLocale()
    {
        $this->assertFalse(Date::setLocale('zz'));
    }
}
