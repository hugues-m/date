<?php

namespace HMLB\Date\Translation;

use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Trait LocalizationCapabilities.
 *
 * @author Hugues Maignol <hugues@hmlb.frr>
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
     *
     * @return bool
     */
    public static function setLocale($locale)
    {
        static::translator()->setLocale($locale);

        return static::loadLocaleResource($locale);
    }

    /**
     * @param string $locale
     *
     * @return bool
     */
    protected static function loadLocaleResource($locale)
    {
        $translator = static::translator();
        if ($translator instanceof Translator) {
            foreach (static::getLocaleResourceFilepaths($locale) as $filepath) {
                if (is_readable($filepath)) {
                    $translator->addResource('array', require $filepath, $locale);

                    return true;
                }
            }

            return false;
        }

        return true;
    }

    /**
     * @param $locale
     *
     * @return string[]
     */
    protected static function getLocaleResourceFilepaths($locale)
    {
        $localePartDelimiters = ['-', '_'];
        $multipart = false;
        foreach ($localePartDelimiters as $delimiter) {
            $locale = explode($delimiter, $locale);
            if (1 !== count($locale)) {
                $multipart = true;
                break;
            }
            $locale = $locale[0];
        }
        if (!$multipart) {
            return [sprintf('%s/Lang/%s.php', __DIR__, $locale)];
        }
        $paths = [];
        foreach ($localePartDelimiters as $delimiter) {
            $paths[] = sprintf('%s/Lang/%s%s%s.php', __DIR__, $locale[0], $delimiter, $locale[1]);
        }

        return $paths;
    }
}
