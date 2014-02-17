<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Orm;

use craft\data\Paginator;
use Craft\Orm\Mapper;
use Craft\Orm\Mapper\PdoMapper;
use Craft\Orm\Mapper\LipsumMapper;
use Craft\Orm\Pdo\MySQL;
use Craft\Orm\Pdo\SQLite;

abstract class Syn
{

    /**
     * Mapper singleton, setup Lipsum as default mapper
     * @param Mapper $mapper
     * @return Mapper
     */
    public static function mapper(Mapper $mapper = null)
    {
        static $instance;
        if($mapper) {
            $instance = $mapper;
        }
        elseif(!$instance) {
            $instance = new LipsumMapper();
        }

        return $instance;
    }


    /**
     * Helper : init mysql
     * @param string $dbname
     * @param array $config
     */
    public static function mysql($dbname, array $config = [])
    {
        // merge config with defaults
        $config = $config + [
            'host' => '127.0.0.1',
            'user' => 'root',
            'pass' => '',
            'prefix' => '',
            'create' => true
        ];

        // init pdo
        $pdo = new MySQL($config['host'], $config['user'], $config['pass']);
        $pdo->open($dbname, $config['create']);

        // init mapper
        $mapper = new PdoMapper($pdo, $config['prefix']);

        static::mapper($mapper);
    }


    /**
     * Helper : init sqlite
     * @param $filename
     * @param null $prefix
     */
    public static function sqlite($filename, $prefix = null)
    {
        // init pdo & mapper
        $pdo = new SQLite($filename);
        $mapper = new PdoMapper($pdo, $prefix);

        static::mapper($mapper);
    }


    /**
     * Register models
     * @param  array $models
     * @return $this
     */
    public static function map(array $models)
    {
        return static::mapper()->map($models);
    }


    /**
     * Get full model namespace
     * @param  string $alias
     * @return string
     */
    public static function model($alias)
    {
        return static::mapper()->model($alias);
    }


    /**
     * Get schema of model
     * @param $alias
     * @return array
     */
    public static function schema($alias)
    {
        return static::mapper()->schema($alias);
    }


    /**
     * Execute a custom sql request
     * @param  string $query
     * @param  string $cast
     * @return array
     */
    public static function query($query, $cast = null)
    {
        return static::mapper()->query($query, $cast);
    }


    /**
     * Count items in collection
     * @param  string $alias
     * @param  array $where
     * @return int
     */
    public static function count($alias, array $where = [])
    {
        return static::mapper()->count($alias, $where);
    }


    /**
     * Find a collection
     * @param  string $alias
     * @param  array $where
     * @param null $orderBy
     * @param null $limit
     * @param null $step
     * @return array
     */
    public static function find($alias, array $where = [], $orderBy = null, $limit = null, $step = null)
    {
        return static::mapper()->find($alias, $where, $orderBy, $limit, $step);
    }


    /**
     * Paginate a collection
     * @param  string $alias
     * @param $size
     * @param $page
     * @param  array $where
     * @param null $orderBy
     * @return Paginator
     */
    public static function paginate($alias, $size, $page, array $where = [], $orderBy = null)
    {
        // calc boundaries
        $total = Syn::count($alias, $where);
        $from = ($size * ($page - 1)) + 1;

        // execute request with limit
        $data = Syn::find($alias, $where, $orderBy, $from, $size);
        return new Paginator($data, $size, $page, $total);
    }


    /**
     * Find a specific entity
     * @param  string $alias
     * @param  mixed $where
     * @return object|\stdClass
     */
    public static function one($alias, $where = null)
    {
        return static::mapper()->one($alias, $where);
    }


    /**
     * Persist entity
     * @param string $alias
     * @param object $entity
     * @return bool
     */
    public static function save($alias, &$entity)
    {
        return static::mapper()->save($alias,$entity);
    }


    /**
     * Delete entity
     * @param  string $alias
     * @param  mixed $entity
     * @return bool
     */
    public static function drop($alias, $entity)
    {
        return static::mapper()->drop($alias, $entity);
    }


    /**
     * Sync model with database
     * @return bool
     */
    public static function merge()
    {
        return static::mapper()->merge();
    }


    /**
     * Create a backup of the database in sql
     * @param $filename string
     * @return bool
     */
    public static function backup($filename)
    {
        return static::mapper()->backup($filename);
    }

}