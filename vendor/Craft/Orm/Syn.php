<?php

namespace Craft\Orm;

use Craft\Orm\Adapter\MySQL;
use Craft\Orm\Adapter\SQLite;
use Forge\Logger;

abstract class Syn
{

    /** DB priority */
    const MASTER = 'master.db';
    const SLAVE = 'slave.db';

    /** @var Database[] */
    protected static $dbs = [];

    /** @var int */
    protected static $use = self::MASTER;


    /**
     * Load database as master
     * @param Database $db
     * @param string $as
     * @return Database
     */
    public static function load(Database $db, $as = self::MASTER)
    {
        static::$dbs[$as] = $db;
        return static::db();
    }


    /**
     * Get database
     * @param string $as
     * @throws \LogicException
     * @return Database
     */
    public static function db($as = null)
    {
        // set db
        if($as) {
            static::$use = $as;
        }

        // no db
        if(!isset(static::$dbs[static::$use])) {
            throw new \LogicException('No database [' . static::$use . '] loaded.');
        }

        return static::$dbs[static::$use];
    }


    /**
     * Map entities to models
     * @param array $models
     * @return Database
     */
    public static function map(array $models)
    {
        return static::db()->map($models);
    }


    /**
     * Get entity
     * @param $entity
     * @return Database\Entity
     */
    public static function get($entity)
    {
        return static::db()->get($entity);
    }


    /**
     * Get many entities
     * @param $entity
     * @param array $where
     * @param null $sort
     * @param null $limit
     * @return array
     */
    public static function all($entity, array $where = [], $sort = null, $limit = null)
    {
        $db = static::db()->get($entity);

        foreach($where as $expression => $value) {
            $db->where($expression, $value);
        }

        if($sort and is_array($sort)) {
            foreach($sort as $field => $sorting) {
                $db->sort($field, $sorting);
            }
        }
        elseif($sort) {
            $db->sort($sort);
        }

        if($limit and is_array($limit)) {
            $db->limit($limit[0], $limit[1]);
        }
        elseif($limit) {
            $db->limit($limit);
        }

        return $db->all();
    }


    /**
     * Get one entities
     * @param $entity
     * @param array $where
     * @return mixed
     */
    public static function one($entity, $where = [])
    {
        $db = static::db()->get($entity);

        // id
        if(is_string($where)) {
            $db->where('id', $where);
        }
        // where
        elseif(is_array($where)) {
            foreach($where as $expression => $value) {
                $db->where($expression, $value);
            }
        }

        return $db->one();
    }


    /**
     * Save entity
     * @param $entity
     * @param $data
     * @return int
     */
    public static function save($entity, $data)
    {
        // parse object
        if(is_object($data)) {
            $data = get_object_vars($data);
        }

        // insert
        if(empty($data['id'])) {
            return static::db()->get($entity)->add($data);
        }

        // update
        return static::db()->get($entity)->where('id', $data['id'])->set($data);
    }


    /**
     * Drop entity
     * @param $entity
     * @param $where
     * @return int
     */
    public static function drop($entity, $where)
    {
        $db = static::db()->get($entity);

        // id
        if(is_string($where)) {
            $db->where('id', $where);
        }
        // where
        elseif(is_array($where)) {
            foreach($where as $expression => $value) {
                $db->where($expression, $value);
            }
        }

        return $db->drop();
    }


    /**
     * Setup mysql
     * @param string $dbname
     * @param array $config
     * @return Database
     */
    public static function MySQL($dbname, array $config = [])
    {
        // create db
        $db = new MySQL($dbname, $config);
        static::load($db);

        Logger::info('Syn : init MySQL database');

        return $db;
    }


    /**
     * Setup mysql
     * @param string $filename
     * @return Database
     */
    public static function SQLite($filename)
    {
        // create db
        $db = new SQLite($filename);
        static::load($db);

        Logger::info('Syn : init SQLite database');

        return $db;
    }

}