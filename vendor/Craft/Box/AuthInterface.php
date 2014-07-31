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

interface AuthInterface
{

    /**
     * Define user seeker
     * @param string|callable $seeker
     * @return mixed
     */
    public function seek($seeker);

    /**
     * Attempt login using seeker
     * @param $username
     * @param $password
     * @param array $opts
     * @return mixed
     */
    public function attempt($username, $password, array $opts = []);

    /**
     * Log user in
     * @param int $rank
     * @param mixed $user
     * @return bool
     */
    public function login($rank = 1, $user = null);

    /**
     * Get rank
     * @return int
     */
    public function rank();

    /**
     * Get user
     * @return mixed
     */
    public function user();

    /**
     * Check if current user is allowed
     * @param int $rank
     * @return bool
     */
    public function allowed($rank);

    /**
     * Log user out
     * @return bool
     */
    public function logout();

} 