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

interface ClassLoaderInterface
{

    /**
     * Register vendor path
     * @param string $vendor
     * @param string $path
     */
    public function add($vendor, $path);

    /**
     * Get vendor path
     * @param string $vendor
     * @throws \RuntimeException
     * @return string
     */
    public function path($vendor);

    /**
     * Register alias
     * @param string $alias
     * @param string $class
     */
    public function alias($alias, $class);

    /**
     * Load a class
     * @param string $class
     * @return bool
     */
    public function load($class);

}