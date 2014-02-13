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

class Query
{

    /** @var array */
    protected $action = [false, null];

    /** @var array */
    protected $join = [];

    /** @var array */
    protected $where = [];

    /** @var array */
    protected $sort = [];

    /** @var string */
    protected $limit = [];

    /**
     * Read env
     * @param array $set
     * @return $this
     */
    public function read(array $set)
    {
        $this->action = ['read', $set];
        return $this;
    }

    /**
     * Insert env
     * @param array $set
     * @return $this
     */
    public function insert(array $set)
    {
        $this->action = ['insert', $set];
        return $this;
    }

    /**
     * Update env
     * @param array $set
     * @return $this
     */
    public function update(array $set)
    {
        $this->action = ['update', $set];
        return $this;
    }

    /**
     * Delete env
     * @param array $set
     * @return $this
     */
    public function delete(array $set)
    {
        $this->action = ['delete', $set];
        return $this;
    }

    /**
     * Where
     * @param $field
     * @param array $where
     * @return $this
     */
    public function where($field, array $where)
    {
        $this->where[] = [$field => $where];
        return $this;
    }

    /**
     * Order by
     * @param $by
     * @param int $sort
     * @return $this
     */
    public function sort($by, $sort = SORT_ASC)
    {
        $this->sort[$by] = $sort;
        return $this;
    }

    /**
     * Limit
     * @param $from
     * @param $length
     * @return $this
     */
    public function limit($from, $length = null)
    {
        $this->limit = [$from];
        if($length) {
            $this->limit[] = $length;
        }

        return $this;
    }

    /**
     * Generate SQL query
     * @throws \RuntimeException
     * @return string
     */
    public function sql()
    {
        // init
        $sql = '# Query generated at ' . date('Y-m-d H:i:s') . "\n";

        // action
        list($action, $set) = $this->action;

        // no action
        if(!$action) {
            throw new \RuntimeException('Query without action !');
        }

        // select
        elseif($action == 'read') {

            // init
            $alias = 'a';
            $select = 'SELECT ';
            $from = 'FROM ';

            // format
            foreach($set as $table => $fields) {

                // add field to select
                $fields = explode(', ', $fields);
                foreach($fields as $field) {
                    $select .= ($field == '*') ? $alias . '.' . $field . ', ' : $alias . '.`' . $field . '`, ';
                }

                // add table to from
                $from .= '`' . trim($table, '`') . '` ' . $alias++ . ', ';
            }

            // clean
            $select = rtrim($select, ', ') . "\n";
            $from = rtrim($from, ', ') . "\n";
            $sql .= $select . $from;

        }

        // insert
        elseif($action == 'insert') {

        }

        // update
        elseif($action == 'insert') {

        }

        // delete
        elseif($action = 'delete') {

        }

        // where
        $vars = [];
        if($this->where) {
            $sql .= 'WHERE 1';
            foreach($this->where as $where) {
                foreach($where as $field => $set) {
                    list($subsql, $subvars) = static::resolve($field, $set);
                    $vars = array_merge($vars, $subvars);
                    $sql .= $subsql;
                }

            }
            $sql .= "\n";
        }

        // order by
        if($this->sort) {
            $sql .= 'ORDER BY ';
            foreach($this->sort as $field => $sort) {
                $sql .= '`' . $field . '` ' . ($sort == SORT_DESC ? 'DESC' : 'ASC') . ', ';
            }
            $sql = rtrim($sql, ', ') . "\n";
        }

        // limit
        if($this->limit) {
            $sql .= 'LIMIT ' . implode(', ', $this->limit);
        }

        return [$sql, $vars];
    }

    /**
     * Alias of $this->sql()
     * @return string
     */
    public function __toString()
    {
        return $this->sql();
    }

    /**
     * Resolve conditions
     * @param $field
     * @param array $set
     * @throws \InvalidArgumentException
     * @return array
     */
    protected static function resolve($field, $set)
    {
        $sql = null;
        $vars = [];

        $i = 0;
        foreach($set as $operator => $value) {

            // and & or
            if(in_array($operator, ['and', 'or', 'xor']) and is_array($value)) {
                list($subsql, $subvars) = static::resolve($field, $value);
                $vars = array_merge($vars, $subvars);
                $sql .= ' ' . strtoupper($operator) . ' (1' . $subsql . ')';
            }

            // simple operator
            elseif(in_array($operator, ['>', '>=', '=', '<=', '<', '<>', 'like'])) {

                if($i == 0) {
                    $sql .= ' AND ';
                }

                // display field name
                if(strpos($field, '.') !== false) {
                    list($alias, $fieldname) = explode('.', $field);
                    $field = $alias . '.`' .trim($fieldname, '`');
                }
                else {
                    $field = '`' . trim($field, '`') . '`';
                }

                // add operator and placeholder
                $placeholder = ':' . uniqid();
                var_dump($placeholder);
                $sql .= $field . ' ' . $operator . ' ' . $placeholder;
                $vars[$placeholder] = $value;
            }

            // range operator
            elseif(in_array($operator, ['between', 'in']) and is_array($value)) {
                // todo
            }

            // error
            else {
                throw new \InvalidArgumentException('Unknown "' . $operator . '" operator.');
            }

            $i++;
        }

        return [$sql, $vars];
    }

}


$query = new Query();

$query->read(['my_table' => '*', 'my_other_table' => 'something'])
      ->where('my_field', ['>' => 5])
      ->where('my_other_field', ['<>' => 20, 'or' => ['>' => 22]]);

var_dump($query->sql());