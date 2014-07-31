<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Forge;

use Craft\Box\Session as SessionProvider;
use Craft\Data\Provider\Container;

abstract class Session extends Container
{

    /**
     * Create provider instance
     * @return SessionProvider
     */
    protected static function bind()
    {
        return new SessionProvider;
    }


    /**
     * Change session provider
     * @param SessionProvider $provider
     */
    public static function swap(SessionProvider $provider)
    {
        static::instance($provider);
    }


    /**
     * Get session id
     * @return mixed
     */
    public static function id()
    {
        return static::instance()->id();
    }


    /**
     * Clear session
     * @return mixed
     */
    public static function clear()
    {
        static::instance()->clear();
    }

} 