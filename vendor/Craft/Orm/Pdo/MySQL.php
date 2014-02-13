<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Orm\Pdo;

class MySQL extends \PDO
{

    /**
     * Init for mysql
     * @param $host
     * @param $username
     * @param $password
     * @param $dbname
     */
    public function __construct($host, $username, $password, $dbname)
    {
        parent::__construct('mysql:host=' . $host . ';dbname=' . $dbname, $username, $password);
    }

} 