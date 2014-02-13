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

use Craft\Reflect\Annotation;

abstract class Mapper
{

    /** @var array */
    protected $models = [];


    /**
     * Register models@
     * @param  array $models
     * @return $this
     */
    public function map(array $models)
    {
        $this->models = $models;
        return $this;
    }


    /**
     * Get full model namespace
     * @param  string $alias
     * @return string
     */
    public function model($alias)
    {
        return isset($this->models[$alias]) ? $this->models[$alias] : false;
    }


    /**
     * Get schema of model
     * @param $alias
     * @return array
     */
    public function schema($alias)
    {
        if($model = $this->model($alias)) {

            // get all properties
            $props = get_object_vars($model);

            // get env type
            $data = [];
            foreach($props as $prop) {
                $data[$prop] = Annotation::property($model, $prop, 'var');
            }

            return $data;
        }

        return false;
    }


    /**
     * Execute a custom query
     * @param string $query
     * @param string $cast
     * @return mixed
     */
    abstract public function query($query, $cast = null);


    /**
     * Count items in collection
     * @param string $alias
     * @param array $where
     * @return int
     */
    abstract public function count($alias, array $where = []);


    /**
     * Find a collection
     * @param string $alias
     * @param array $where
     * @param string|array $orderBy
     * @param int $limit
     * @param int $step
     * @return array
     */
    abstract public function find($alias, array $where = [], $orderBy = null, $limit = null, $step = null);


    /**
     * Find a specific entity
     * @param string $alias
     * @param mixed $where
     * @return object|\stdClass
     */
    abstract public function one($alias, $where = null);


    /**
     * Persist entity
     * @param string $alias
     * @param object $entity
     * @return bool
     */
    abstract public function save($alias, &$entity);


    /**
     * Delete entity
     * @param string $alias
     * @param mixed $entity
     * @return bool
     */
    abstract public function drop($alias, $entity);


    /**
     * Sync model with database
     * @return bool
     */
    abstract public function merge();


    /**
     * Create a backup of the database in sql
     * @param $filename string
     * @return bool
     */
    abstract public function backup($filename);

}