<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Pattern;

trait Multiton
{

    /** @var self[] */
    protected static $instances = [];


    /**
     * Private constructors
     */
    protected function __construct() {}
    protected function __clone() {}


    /**
     * Get singleton instance by name
     * @param $name
     * @return Multiton
     */
    public function instance($name)
    {
        // lazy init
        if(!isset(static::$instances[$name])) {
            static::$instances[$name] = new self();
        }

        return static::$instances[$name];
    }

} 