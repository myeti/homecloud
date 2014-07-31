<?php

namespace Craft\Orm;

trait Model
{

    /**
     * Get entity
     * @return Database\Entity
     */
    public static function get()
    {
        return Syn::get(get_called_class());
    }


    /**
     * Get many entities
     * @param array $where
     * @param int $sort
     * @param mixed $limit
     * @return static[]
     */
    public static function all(array $where = [], $sort = null, $limit = null)
    {
        return Syn::all(get_called_class(), $where, $sort, $limit);
    }


    /**
     * Get one entity
     * @param mixed $where
     * @return static
     */
    public static function one($where = [])
    {
        return Syn::one(get_called_class(), $where);
    }


    /**
     * Save entity
     * @param mixed $data
     * @return int
     */
    public static function save($data)
    {
        return Syn::save(get_called_class(), $data);
    }


    /**
     * Drop entity
     * @param $id
     * @return int
     */
    public static function drop($id)
    {
        return Syn::drop(get_called_class(), $id);
    }


    /**
     * Many relation
     * @param $entity
     * @param $foreign
     * @param string $local
     * @return mixed
     */
    protected function _many($entity, $foreign, $local = 'id')
    {
        return Syn::get($entity)->where($foreign, $this->{$local})->all();
    }


    /**
     * One relation
     * @param $entity
     * @param $local
     * @param string $foreign
     * @return mixed
     */
    protected function _one($entity, $local, $foreign = 'id')
    {
        return Syn::get($entity)->where($foreign, $this->{$local})->one();
    }

} 