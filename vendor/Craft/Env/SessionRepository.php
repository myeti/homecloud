<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Env;

use Craft\Data\Repository;

class SessionRepository extends Repository
{

    /** @var bool */
    protected $serialize = false;

    /**
     * Setup session in repository
     * @param string $base
     * @param bool $serialize
     */
    public function __construct($base, $serialize = false)
    {
        if(!isset($_SESSION[$base])) {
            $_SESSION[$base] = [];
        }

        $this->serialize = $serialize;

        parent::__construct($_SESSION[$base]);
    }


    /**
     * Get content
     * @param $key
     * @param null $fallback
     * @return mixed|void
     */
    public function get($key, $fallback = null)
    {
        $data = parent::get($key);

        if($this->serialize) {
            return $data ? unserialize($data) : $fallback;
        }

        return $data ?: $fallback;
    }

    /**
     * Set content
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value)
    {
        return $this->serialize
            ? parent::set($key, serialize($value))
            : parent::set($key, $value);
    }

}