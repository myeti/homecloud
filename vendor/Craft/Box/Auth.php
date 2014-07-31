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

use Craft\Orm\Syn;
use Forge\Logger;

class Auth implements AuthInterface
{

    /** @var SessionInterface */
    protected $session;

    /** @var callable */
    protected $seeker;


    /**
     * Bind to session
     * @param string|callable $seeker
     */
    public function __construct($seeker = null)
    {
        // set session storage strategy
        $this->session = new Session\Storage('craft/auth');

        // define authenticator
        if($seeker) {
            $this->seek($seeker);
        }
    }


    /**
     * Define authenticator
     * @param string|callable $seeker
     * @return mixed|void
     * @throws \InvalidArgumentException
     */
    public function seek($seeker)
    {
        // custom callable
        if(is_callable($seeker)) {
            $this->seeker = $seeker;
        }
        // handle user class
        elseif(class_exists($seeker)) {
            $this->seeker = function($username, $password, array $opts = []) use($seeker) {

                // get user
                return Syn::one($seeker, [
                    'username' => $username,
                    'password' => sha1($password)
                ]);

            };
        }
        else {
            throw new \InvalidArgumentException('Invalid callable or class');
        }

        Logger::info('Auth : change user model seeker');
    }


    /**
     * Attempt login
     * @param string $username
     * @param string $password
     * @param array $opts
     * @return bool|object
     */
    public function attempt($username, $password, array $opts = [])
    {
        // use authenticator
        if(is_callable($this->seeker)) {

            // login
            if($user = call_user_func_array($this->seeker, [$username, $password, $opts])) {
                $this->login($user->rank ?: 1, $user);
                return $user;
            }

        }

        return false;
    }


    /**
     * Log user in
     * @param int $rank
     * @param mixed $user
     * @return bool
     */
    public function login($rank = 1, $user = null)
    {
        $this->session->set('rank', $rank);
        $this->session->set('user', $user);
    }


    /**
     * Get rank
     * @return int
     */
    public function rank()
    {
        return (int)$this->session->get('rank');
    }


    /**
     * Get user
     * @return mixed
     */
    public function user()
    {
        return $this->session->get('user');
    }


    /**
     * Log user out
     * @return bool
     */
    public function logout()
    {
        $this->session->clear();
    }


    /**
     * Check if current user is allowed
     * @param int $rank
     * @return bool
     */
    public function allowed($rank)
    {
        return $this->rank() >= $rank;
    }
}
