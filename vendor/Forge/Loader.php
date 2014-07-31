<?php
/**
 * This file is part of the Forge package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Forge;

use Craft\Reflect\ClassLoader;
use Craft\Reflect\ClassLoaderInterface;

abstract class Loader
{

    /** @var ClassLoaderInterface */
    protected static $instance;


    /**
     * Get or set ClassLoader instance
     * @return ClassLoaderInterface
     */
    protected static function instance()
    {
        if(!static::$instance) {
            static::$instance = new ClassLoader;
        }

        return static::$instance;
    }


    /**
     * Set custom class loader
     * @param ClassLoaderInterface $loader
     * @return ClassLoaderInterface
     */
    public static function set(ClassLoaderInterface $loader)
    {
        static::$instance = $loader;

        return static::$instance;
    }


    /**
     * Register vendor path
     * @param string $vendor
     * @param string $path
     * @return ClassLoaderInterface
     */
    public static function add($vendor, $path)
    {
        static::instance()->add($vendor, $path);

        return static::$instance;
    }


    /**
     * Get vendor path
     * @param string $vendor
     * @throws \RuntimeException
     * @return string
     */
    public static function path($vendor)
    {
        return static::instance()->path($vendor);
    }


    /**
     * Register alias
     * @param string $alias
     * @param string $class
     * @return ClassLoaderInterface
     */
    public static function alias($alias, $class)
    {
        static::instance()->alias($alias, $class);

        return static::$instance;
    }


    /**
     * Load a class
     * @param string $class
     * @return bool
     */
    public static function load($class)
    {
        return static::instance()->load($class);
    }

}