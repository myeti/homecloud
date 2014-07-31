<?php

namespace Craft\View\Helper;

use Forge\Auth;
use Forge\Flash;
use Forge\Session;
use Craft\View\Helper;

class Box extends Helper
{

    /**
     * Read-only session
     * @param $key
     * @return mixed
     */
    public function session($key)
    {
        return Session::get($key);
    }


    /**
     * Read-only flash
     * @param $key
     * @return mixed
     */
    public function flash($key)
    {
        return Flash::get($key);
    }


    /**
     * Read-only auth
     * @return object
     */
    public function auth()
    {
        return (object)[
            'rank'      => Auth::rank(),
            'user'      => Auth::user()
        ];
    }

} 