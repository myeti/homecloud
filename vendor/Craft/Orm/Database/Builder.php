<?php

namespace Craft\Orm\Database;

use Craft\Reflect\Annotation;

class Builder
{

    /** @var array */
    protected $syntax = [
        'primary'       => 'PRIMARY KEY AUTO_INCREMENT',
        'null'          => 'NOT NULL',
        'default'       => 'DEFAULT',
    ];

    /** @var array */
    protected $types = [
        'varchar'           => 'VARCHAR(255)',
        'string'            => 'VARCHAR(255)',
        'string email'      => 'VARCHAR(255)',
        'string text'       => 'TEXT',
        'string date'       => 'DATE',
        'string datetime'   => 'DATETIME',
        'int'               => 'INTEGER',
        'bool'              => 'BOOLEAN',
    ];

    /** @var array */
    protected $defaults = [
        'type'      => 'VARCHAR(255)',
        'primary'   => false,
        'null'      => true,
        'default'   => 'NULL'
    ];

    /** @var array */
    protected static $resolved = [];

    /**
     * Create entity
     * @param $entity
     * @return mixed
     */
    public function create($entity)
    {
        // write create
        $sql = 'CREATE TABLE IF NOT EXISTS `' . static::resolve($entity) . '` (';

        // each field
        $fields = get_class_vars($entity);
        foreach($fields as $field => $null) {

            // resolve type
            $type = Annotation::property($entity, $field, 'var') ?: 'string';

            // define opts
            $opts = ['type' => trim($type)] + $this->defaults;

            // parse type
            if(isset($this->types[$opts['type']])) {
                $opts['type'] = $this->types[$opts['type']];
            }

            // write field type
            $sql .= '`' . $field . '` ' . $opts['type'];

            // primary
            if($field === 'id') {
                $opts['primary'] = true;
                $opts['default'] = null;
                $sql .= ' ' . $this->syntax['primary'];
            }

            // null
            if(!$opts['null']) {
                $sql .= ' ' . $this->syntax['null'];
            }

            // default
            if($opts['default']) {
                $sql .= ' ' . $this->syntax['default'] . ' ' . $opts['default'];
            }

            // end of line
            $sql .= ',';
        }

        // close sql
        $sql = trim($sql, ',') . ');';

        return $sql;
    }


    /**
     * Remove entity
     * @param $entity
     * @return mixed
     */
    public function clear($entity)
    {
        return 'TRUNCATE `' . static::resolve($entity) . '`;';
    }


    /**
     * Remove entity
     * @param $entity
     * @return mixed
     */
    public function wipe($entity)
    {
        return 'DROP TABLE `' . static::resolve($entity) . '`;';
    }


    /**
     * Resolve entity name
     * @param $entity
     * @return string
     */
    public static function resolve($entity)
    {
        if(!isset(static::$resolved[$entity])) {
            static::$resolved[$entity] = Annotation::object($entity, 'entity') ?: end(explode('\\', $entity));
        }

        return static::$resolved[$entity];
    }

}