<?php

namespace src\Translation;

use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Trait LocalizationCapabilities.
 *
 * @author Hugues Maignol <hugues.maignol@kitpages.fr>
 */
trait DateLocalizationCapabilities
{
    /**
     * A translator to ... er ... translate stuff.
     *
     * @var TranslatorInterface
     */
    protected static $translator;

    ///////////////////////////////////////////////////////////////////
    /////////////////////// LOCALIZATION //////////////////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * Intialize the translator instance if necessary.
     *
     * @return TranslatorInterface
     */
    protected static function translator()
    {
        if (static::$translator === null) {
            $translator = new Translator('en');
            $translator->addLoader('array', new ArrayLoader());
            static::$translator = $translator;
            static::setLocale('en');
        }

        return static::$translator;
    }

    /**
     * Get the translator instance in use.
     *
     * @return TranslatorInterface
     */
    public static function getTranslator()
    {
        return static::translator();
    }

    /**
     * Set the translator instance to use.
     *
     * @param TranslatorInterface $translator
     */
    public static function setTranslator(TranslatorInterface $translator)
    {
        static::$translator = $translator;
    }

    /**
     * Get the current translator locale.
     *
     * @return string
     */
    public static function getLocale()
    {
        return static::translator()->getLocale();
    }

    /**
     * Set the current translator locale.
     *
     * @param string $locale
     */
    public static function setLocale($locale)
    {
        static::translator()->setLocale($locale);
        static::loadLocaleResource($locale);
    }

    protected static function loadLocaleResource($locale)
    {
        $translator = static::translator();
        if ($translator instanceof Translator) {
            $localeFile = sprintf('%s/Lang/%s.php', __DIR__, $locale);
            $translator->addResource('array', require $localeFile, $locale);
        }
    }
}
