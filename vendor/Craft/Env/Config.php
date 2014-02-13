<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Env;

use Craft\Data\Repository;
use Craft\Data\StaticProvider;

abstract class Config extends StaticProvider
{

    /**
     * Create provider instance
     * @return Repository
     */
    protected static function createInstance()
    {
        return new Repository($_ENV);
    }


    /**
     * Get or set timezone
     * @param string $timezone
     * @return string
     */
    public static function timezone($timezone = null)
    {
        if($timezone) {
            date_default_timezone_set($timezone);
        }

        return date_default_timezone_get();
    }


    /**
     * Get or set locale
     * @param $lang
     * @return string
     */
    public static function locale($lang = null)
    {
        if($lang) {
            setlocale(LC_ALL, $lang);
            locale_set_default($lang);
        }

        return locale_get_default();
    }

} 