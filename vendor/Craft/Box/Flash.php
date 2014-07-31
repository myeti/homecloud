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

use Craft\Data\ProviderInterface;

class Flash implements ProviderInterface
{

    /** @var SessionInterface */
    protected $session;


    /**
     * Bind to session
     */
    public function __construct()
    {
        $this->session = new Session\Storage('craft/flash');
    }


    /**
     * Get all elements
     * @return array
     */
    public function all()
    {
        return $this->session->all();
    }


    /**
     * Check if element exists
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return $this->session->has($key);
    }


    /**
     * Consume element
     * @param $key
     * @param null $fallback
     * @return mixed
     */
    public function get($key, $fallback = null)
    {
        $message = $this->session->get($key, $fallback);
        $this->drop($key);
        return $message;
    }


    /**
     * Set element by key with value
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value)
    {
        $this->session->set($key, $value);
    }


    /**
     * Drop element by key
     * @param $key
     * @return bool
     */
    public function drop($key)
    {
        $this->session->drop($key);
    }


    /**
     * Clear all elements
     * @return bool
     */
    public function clear()
    {
        $this->session->clear();
    }

}