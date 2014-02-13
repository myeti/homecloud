<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Reflect;

class ClassLoader
{

    /** @var array */
    protected $vendors = [];


    /**
     * Register vendor path
     * @param string $prefix
     * @param string $path
     */
    public function vendor($prefix, $path)
    {
        // clean
        $prefix = trim($prefix, '\\');
        $path = str_replace('\\', DIRECTORY_SEPARATOR , $path);
        $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        // register
        $this->vendors[$prefix] = $path;
    }


    /**
     * Register many vendors path
     * @param array $vendors
     */
    public function vendors(array $vendors)
    {
        foreach($vendors as $prefix => $path) {
            static::vendor($prefix, $path);
        }
    }


    /**
     * Register alias
     * @param string $alias
     * @param string $class
     */
    public function alias($alias, $class)
    {
        class_alias($class, $alias);
    }


    /**
     * Register many aliases
     * @param array $aliases
     */
    public function aliases(array $aliases)
    {
        foreach($aliases as $alias => $class) {
            static::alias($alias, $class);
        }
    }


    /**
     * Auto-register as Autoloader
     */
    public function register()
    {
        spl_autoload_register([$this, 'load']);
    }


    /**
     * Load a class
     * @param string $class
     * @throws \RuntimeException
     * @return bool
     */
    public function load($class)
    {
        // clean
        $class = str_replace('\\', DIRECTORY_SEPARATOR , $class);
        $class .= '.php';

        // has vendor ?
        foreach($this->vendors as $vendor => $path) {

            // prefix matching
            $length = strlen($vendor);
            if(substr($class, 0, $length) === $vendor) {

                // make real path
                $filename = $path . substr($class, $length);

                // class exists
                if(file_exists($filename)) {
                    require $filename;
                    return true;
                }
            }

        }

        return false;
    }


    /**
     * Get vendor path
     * @param string $vendor
     * @throws \RuntimeException
     * @return string
     */
    public function path($vendor)
    {
        // clean
        $vendor = trim($vendor, '\\');

        // error
        if(!isset($this->vendors[$vendor])) {
            throw new \RuntimeException('Vendor "' . $vendor . '" does not exists.');
        }

        return $this->vendors[$vendor];
    }

}