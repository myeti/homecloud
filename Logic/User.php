<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace My\Logic;

use Forge\Auth;
use Forge\Flash;

class User
{

    /**
     * Attempt login
     * @render views/user.login
     * @return mixed
     */
    public function login()
    {
        // login submitted
        if(post())
        {
            // extract env
            $username = post('username');
            $password = post('password');

            // login ok
            if($username == HC_USERNAME and sha1($password) == HC_PASSWORD) {
                Auth::login(1, $username);
                go('/');
            }
            else {
                Flash::set('login.failed', 'Wrong user.');
            }
        }
    }


    /**
     * Logout current user
     */
    public function logout()
    {
        Auth::logout();
        go('/login');
    }

} 