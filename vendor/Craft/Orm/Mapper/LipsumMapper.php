<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Orm\Mapper;

use Craft\Orm\Mapper;
use Craft\Text\Lipsum;

class LipsumMapper extends Mapper
{

    /**
     * Execute a custom sql request
     * @param  string $query
     * @param  string $cast
     * @return array
     */
    public function query($query, $cast = null)
    {
        return [];
    }


    /**
     * Count items in collection
     * @param  string $alias
     * @param  array $where
     * @return int
     */
    public function count($alias, array $where = [])
    {
        return count($this->find($alias, $where));
    }


    /**
     * Find a collection
     * @param  string $alias
     * @param  array $where
     * @param null $orderBy
     * @param null $limit
     * @param null $step
     * @throws \PDOException
     * @return array
     */
    public function find($alias, array $where = [], $orderBy = null, $limit = null, $step = null)
    {
        // init
        $max = $limit ? ($step ?: $limit) : rand(4, 20);
        if(empty($this->models[$alias])) {
            throw new \PDOException('No alias named "' . $alias . '".');
        }
        $model = $this->models[$alias];

        // get schema
        $schema = $this->schema($alias);

        // populate
        $data = [];
        foreach(range(0, $max) as $i) {

            // init & populate
            $entity = new $model();
            foreach($schema as $field => $type) {

                // int
                if($type == 'int') { $entity->{$field} = Lipsum::number(); }
                // text
                elseif($type == 'string text') { $entity->{$field} = Lipsum::paragraph(); }
                // date
                elseif($type == 'string date') { $entity->{$field} = Lipsum::date(); }
                // string
                else { $entity->{$field} = Lipsum::line(); }

            }

            $data[] = $entity;
        }

        return $data;
    }


    /**
     * Find a specific entity
     * @param  string $alias
     * @param  mixed $where
     * @return object|\stdClass
     */
    public function one($alias, $where = null)
    {
        $data = $this->find($alias, [], null, 1);
        return current($data);
    }


    /**
     * Persist entity
     * @param string $alias
     * @param object $entity
     * @return bool
     */
    public function save($alias, &$entity)
    {
        return true;
    }


    /**
     * Delete entity
     * @param  string $alias
     * @param  mixed $entity
     * @return bool|int
     */
    public function drop($alias, $entity)
    {
        return true;
    }


    /**
     * Sync model with database
     * @return bool
     */
    public function merge()
    {
        return true;
    }


    /**
     * Create a backup of the database in sql
     * @param $filename string
     * @return bool
     */
    public function backup($filename)
    {
        return true;
    }

} 