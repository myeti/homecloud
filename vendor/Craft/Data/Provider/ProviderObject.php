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

use Craft\Data\ProviderInterface;

class ProviderObject extends \ArrayObject implements ProviderInterface
{

    /**
     * Get all elements
     * @return array
     */
    public function all()
    {
        return $this->getArrayCopy();
    }


    /**
     * Check if element exists
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        $has = true;
        foreach(func_get_args() as $key) {
            $has &= isset($this[$key]);
        }
        return $has;
    }


    /**
     * Get element by key, fallback on error
     * @param $key
     * @param null $fallback
     * @return mixed
     */
    public function get($key, $fallback = null)
    {
        return isset($this[$key]) ? $this[$key] : $fallback;
    }


    /**
     * Set element by key with value
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value)
    {
        $this[$key] = $value;
        return true;
    }


    /**
     * Drop element by key
     * @param $key
     * @return bool
     */
    public function drop($key)
    {
        foreach(func_get_args() as $key) {
            unset($this[$key]);
        }
        return true;
    }


    /**
     * Clear data
     * @return $this
     */
    public function clear()
    {
        $this->exchangeArray([]);
        return $this;
    }

}