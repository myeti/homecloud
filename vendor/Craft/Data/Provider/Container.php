<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Data\Provider;

use Craft\Pattern\StaticSingleton;

abstract class Container
{

    use StaticSingleton;

    /**
     * Get all elements
     * @return bool
     */
    public static function all()
    {
        return static::instance()->all();
    }

    /**
     * Check if element exists
     * @param $key
     * @return bool
     */
    public static function has($key)
    {
        return static::instance()->has($key);
    }

    /**
     * Get element by key, fallback on error
     * @param $key
     * @param null $fallback
     * @return mixed
     */
    public static function get($key, $fallback = null)
    {
        return static::instance()->get($key, $fallback);
    }

    /**
     * Set element by key with value
     * @param $key
     * @param $value
     * @return bool
     */
    public static function set($key, $value)
    {
        return static::instance()->set($key, $value);
    }

    /**
     * Drop element by key
     * @param $key
     * @return bool
     */
    public static function drop($key)
    {
        return static::instance()->drop($key);
    }

    /**
     * Clear all elements
     * @return bool
     */
    public static function clear()
    {
        return static::instance()->clear();
    }

} 