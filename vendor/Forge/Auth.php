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

use Craft\Box\Auth as AuthProvider;
use Craft\Data\Provider\Container;

abstract class Auth
{

    /**
     * Get singleton instance
     * @return AuthProvider;
     */
    protected static function instance()
    {
        static $instance;
        if(!$instance) {
            $instance = new AuthProvider;
        }

        return $instance;
    }


    /**
     * Define authenticator
     * @param string|callable $seeker
     * @return mixed
     */
    public static function seek($seeker)
    {
        return static::instance()->seek($seeker);
    }


    /**
     * Attempt login
     * @param string $username
     * @param string $password
     * @param array $opts
     * @return bool|object
     */
    public static function attempt($username, $password, array $opts = [])
    {
        return static::instance()->attempt($username, $password, $opts);
    }


    /**
     * Get rank
     * @return int
     */
    public static function rank()
    {
        return static::instance()->rank();
    }


    /**
     * Get stored user
     * @return mixed
     */
    public static function user()
    {
        return static::instance()->user();
    }


    /**
     * Log user in
     * @param int  $rank
     * @param mixed $user
     */
    public static function login($rank = 1, $user = null)
    {
        static::instance()->login($rank, $user);
    }


    /**
     * Log user out
     */
    public static function logout()
    {
        static::instance()->logout();
    }


    /**
     * Check if user is allowed
     * @param int $rank
     * @return bool
     */
    public static function allowed($rank)
    {
        return static::instance()->allowed($rank);
    }

}