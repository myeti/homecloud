<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Box;

class Session implements SessionInterface
{

    /** @var SessionInterface */
    protected $provider;


    /**
     * Setup session
     */
    public function __construct()
    {
        $this->provider = new Session\Storage('craft/session');
    }


    /**
     * Get session id
     * @return string
     */
    public function id()
    {
        return $this->provider->id();
    }


    /**
     * Get all data
     * @return mixed
     */
    public function all()
    {
        $this->provider->all();
    }


    /**
     * Check if element exists
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return $this->provider->has($key);
    }


    /**
     * Get element by key, fallback on error
     * @param $key
     * @param null $fallback
     * @return mixed
     */
    public function get($key, $fallback = null)
    {
        return $this->provider->get($key, $fallback);
    }


    /**
     * Set element by key with value
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value)
    {
        return $this->provider->set($key, $value);
    }


    /**
     * Drop element by key
     * @param $key
     * @return bool
     */
    public function drop($key)
    {
        return $this->provider->drop($key);
    }


    /**
     * Destroy session
     * @return mixed
     */
    public function clear()
    {
        $this->provider->clear();
    }

}