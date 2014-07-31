<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Data;

interface ProviderInterface
{

    /**
     * Get all elements
     * @return array
     */
    public function all();

    /**
     * Check if element exists
     * @param $key
     * @return bool
     */
    public function has($key);

    /**
     * Get element by key, fallback on error
     * @param $key
     * @param null $fallback
     * @return mixed
     */
    public function get($key, $fallback = null);

    /**
     * Set element by key with value
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value);

    /**
     * Drop element by key
     * @param $key
     * @return bool
     */
    public function drop($key);


    /**
     * Clear all elements
     * @return bool
     */
    public function clear();

} 