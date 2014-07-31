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

use Craft\Data\Provider\ProviderObject;

class Cookie extends ProviderObject
{

    /**
     * Create provider instance
     */
    public function __construct()
    {
        parent::__construct($_COOKIE);
    }


    /**
     * Set cookie
     * @param string $key
     * @param mixed $value
     * @param int $expire
     * @return $this|void
     */
    public function set($key, $value, $expire = 0)
    {
        setcookie($key, $value, time() + $expire);
        return parent::set($key, $value);
    }


    /**
     * Drop value to 0
     * @param $key
     * @return bool|void
     */
    public function drop($key)
    {
        setcookie($key, null);
        return parent::drop($key);
    }

}