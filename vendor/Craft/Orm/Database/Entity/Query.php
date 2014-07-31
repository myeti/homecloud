<?php

namespace Craft\Orm\Database\Entity;

use Craft\Orm\Database\Builder;

class Query
{

    /** @var string */
    protected $entity;

    /** @var string */
    protected $action = 'select';

    /** @var array */
    protected $select = ['*'];

    /** @var array */
    protected $insert = [];

    /** @var array */
    protected $update = [];

    /** @var array */
    protected $where = [];

    /** @var array */
    protected $values = [];

    /** @var array */
    protected $sort = [];

    /** @var string */
    protected $limit;

    /** @var array */
    protected $operators = ['>', '>=', '<', '<=', '=', 'is', 'not', 'in', 'exists'];


    /**
     * Set entity
     * @param $entity
     */
    public function __construct($entity)
    {
        $this->entity = Builder::resolve($entity);
    }


    /**
     * Select fields
     * @param array $fields
     * @return $this
     */
    public function select(array $fields)
    {
        $this->action = 'select';
        $this->select = $fields;
        return $this;
    }


    /**
     * Insert data
     * @param array $data
     * @return $this
     */
    public function insert(array $data)
    {
        $this->action = 'insert';
        $this->insert = $data;
        return $this;
    }


    /**
     * Update data
     * @param array $data
     * @return $this
     */
    public function update(array $data)
    {
        $this->action = 'update';
        $this->update = $data;
        return $this;
    }


    /**
     * Delete data
     * @return $this
     */
    public function delete()
    {
        $this->action = 'delete';
        return $this;
    }


    /**
     * Add where clause
     * @param $expression
     * @param mixed $value
     * @return $this
     */
    public function where($expression, $value = null)
    {
        // get values
        $values = func_get_args();
        array_shift($values);

        // parse last
        $split = explode(' ', $expression);
        $last = end($split);

        // case 1 : missing '= ?'
        if(preg_match('/^[a-zA-Z_0-9]+$/', $expression)) {
            $expression .= ' = ?';
        }
        // case 2 : missing '?'
        elseif(in_array($last, $this->operators)) {
            if(is_array($value)) {
                $values = $value;
                $placeholders = array_fill(0, count($values), '?');
                $expression .= ' (' . implode(', ', $placeholders) . ')';
            }
            else {
                $expression .= ' ?';
            }
        }

        $this->where[$expression] = $values;
        return $this;
    }


    /**
     * Sort data
     * @param $field
     * @param int $sort
     * @return $this
     */
    public function sort($field, $sort = SORT_ASC)
    {
        $this->sort[$field] = $sort;
        return $this;
    }


    /**
     * Limit rows
     * @param int $i
     * @param int $step
     * @return $this
     */
    public function limit($i, $step = 0)
    {
        $this->limit = $i;
        if($step) {
            $this->limit .= ', ' . $step;
        }
        return $this;
    }


    /**
     * Generate sql & values
     * @return string
     */
    public function generate()
    {
        // init
        $sql = $values = [];

        // select
        if($this->action == 'select') {

            $sql[] = 'SELECT ' . implode(', ', $this->select);
            $sql[] = 'FROM `' . $this->entity . '`';

            if($this->where) {
                $where = [];
                foreach($this->where as $exp => $data) {
                    $where[] = $exp;
                    $values = array_merge($values, $data);
                }
                $sql[] = 'WHERE ' . implode(' AND ', $where);
            }

            if($this->sort) {
                $sort = [];
                foreach($this->sort as $field => $sens) {
                    $sort[] = '`' . $field . '` ' . ($sens == SORT_DESC ? 'DESC' : 'ASC');
                }
                $sql[] = 'ORDER BY ' .implode(', ', $sort);
            }

            if($this->limit) {
                $sql[] = 'LIMIT ' . $this->limit;
            }

        }
        // insert
        elseif($this->action == 'insert') {

            $sql[] = 'INSERT INTO `' . $this->entity . '`';

            $fields = $holders = [];
            foreach($this->insert as $field => $value) {
                $fields[] = '`' . $field . '`';
                $holders[] = '?';
                $values[] = $value;
            }
            $sql[] = '(' . implode(', ', $fields) . ')';
            $sql[] = 'VALUES (' . implode(', ', $holders) . ')';

        }
        // update
        elseif($this->action == 'update') {

            $sql[] = 'UPDATE `' . $this->entity . '`';
            $fields = [];
            foreach($this->update as $field => $value) {
                $fields[] = '`' . $field . '` = ?';
                $values[] = $value;
            }
            $sql[] = 'SET ' . implode(', ', $fields);

            if($this->where) {
                $where = [];
                foreach($this->where as $exp => $data) {
                    $where[] = $exp;
                    $values = array_merge($values, $data);
                }
                $sql[] = 'WHERE ' . implode(' AND ', $where);
            }

        }
        // delete
        elseif($this->action == 'delete') {

            $sql[] = 'DELETE FROM `' . $this->entity . '`';

            if($this->where) {
                $where = [];
                foreach($this->where as $exp => $data) {
                    $where[] = $exp;
                    $values = array_merge($values, $data);
                }
                $sql[] = 'WHERE ' . implode(' AND ', $where);
            }

        }

        $sql = implode("\n", $sql);
        return [$sql, $values];
    }

} 