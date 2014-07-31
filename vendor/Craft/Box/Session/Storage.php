<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Box\Session;

use Craft\Box\SessionInterface;
use Craft\Data\Serializer;
use Craft\Data\Repository;

class Storage extends Serializer implements SessionInterface
{

    /** @var string */
    protected $name;

    /**
     * init session storage
     * @param string $name
     */
    public function __construct($name)
    {
        // start
        if(!session_id()) {
            ini_set('session.use_trans_sid', 0);
            ini_set('session.use_only_cookies', 1);
            ini_set("session.cookie_lifetime", 604800);
            ini_set("session.gc_maxlifetime", 604800);
            session_set_cookie_params(604800);
            session_start();
        }

        // init storage provider
        $this->name = $name;
        $session = isset($_SESSION[$name]) ? $_SESSION[$name] : [];

        parent::__construct(new Repository($session));
    }


    /**
     * Get session id
     * @return string
     */
    public function id()
    {
        return session_id();
    }


    /**
     * Set and save
     * @param $key
     * @param $value
     * @return bool|void
     */
    public function set($key, $value)
    {
        parent::set($key, $value);
        $this->save();
    }


    /**
     * Drop and save
     * @param $key
     * @return bool|void
     */
    public function drop($key)
    {
        parent::drop($key);
        $this->save();
    }


    /**
     * Destroy session
     * @return bool
     */
    public function clear()
    {
        parent::clear();
        $this->save();
    }


    /**
     * Replicate inner data into external source
     * @return mixed
     */
    protected function save()
    {
        $_SESSION[$this->name] = $this->all();
    }

}