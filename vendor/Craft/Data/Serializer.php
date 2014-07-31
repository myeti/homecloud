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

class Serializer implements ProviderInterface
{

    /** @var ProviderInterface */
    protected $provider;


    /**
     * Set subject provider
     * @param ProviderInterface $provider
     */
    public function __construct(ProviderInterface $provider)
    {
        $this->provider = $provider;
    }


    /**
     * Get all elements
     * @return array
     */
    public function all()
    {
        return $this->provider->all();
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
     * Filter in
     * @param $key
     * @param $value
     * @return mixed
     */
    public function set($key, $value)
    {
        if(!is_scalar($value)) {
            $value = serialize($value);
        }

        return $this->provider->set($key, $value);
    }


    /**
     * Filter out
     * @param $key
     * @param null $fallback
     * @return mixed
     */
    public function get($key, $fallback = null)
    {
        $value = $this->provider->get($key, $fallback);

        if(is_string($value)) {
            $decrypted = @unserialize($value);
            if($decrypted !== false or $value == 'b:0;') {
                $value = $decrypted;
            }
        }

        return $value;
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
     * Clear all elements
     * @return bool
     */
    public function clear()
    {
        return $this->provider->clear();
    }
}