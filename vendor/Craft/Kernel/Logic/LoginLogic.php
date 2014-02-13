<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Kernel\Logic;

use Craft\Env\Auth;

trait LoginLogic
{

    /**
     * Attempt login
     * @return mixed
     */
    public function login()
    {
        // login submitted
        if(post())
        {
            // extract env
            $username = post('username');
            $password = post('username');

            // env ok ?
            if($user = $this->getUser($username, $password)) {

                // login
                $rank = $this->getRank($user);
                Auth::login($rank, $user);

                return $this->onLoginOk($user);
            }
            else {
                return $this->onLoginFailed();
            }
        }

        return $this->onLoginEnd();
    }

    /**
     * Check if user exists and return it or false
     * @param $username
     * @param $password
     * @return mixed
     */
    abstract protected function getUser($username, $password);

    /**
     * Get user rank
     * @param $user
     * @return mixed
     */
    abstract protected function getRank($user);

    /**
     * Action is login ok
     * @param $user
     * @return mixed
     */
    abstract protected function onLoginOk($user);

    /**
     * Action if login failed
     * @return mixed
     */
    abstract protected function onLoginFailed();

    /**
     * Action if nothing happened
     * @return mixed
     */
    abstract protected function onLoginEnd();


    /**
     * Logout current user
     */
    public function logout()
    {
        Auth::logout();
        return $this->onLogout();
    }

    /**
     * Action if logout
     * @return mixed
     */
    abstract protected function onLogout();

} 