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
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $dbname
     */
    public function __construct($host, $username, $password, $dbname = null)
    {
        $connector = 'mysql:host=' . $host;
        if($dbname) {
            $connector .= ';dbname=' . $dbname;
        }

        parent::__construct($connector, $username, $password);
    }


    /**
     * Open or create database
     * @param $dbname
     * @param bool $create
     * @return \PDOStatement
     */
    public function open($dbname, $create = true)
    {
        if($create) {
            $sql = 'CREATE DATABASE IF NOT EXISTS `:dbname`;';
            $this->prepare($sql)->execute([':dbname' => $dbname]);
        }

        return $this->prepare('USE `:dbname`;')->execute([':dbname' => $dbname]);
    }

} 