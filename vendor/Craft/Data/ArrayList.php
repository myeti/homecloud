<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Data;

class ArrayList extends Provider\ProviderObject
{

    /**
     * Get first element
     * @return mixed
     */
    public function first()
    {
        return reset($this);
    }


    /**
     * Get first key
     * @return mixed
     */
    public function firstKey()
    {
        reset($this);
        return key($this);
    }


    /**
     * Get last element
     * @return mixed
     */
    public function last()
    {
        return end($this);
    }


    /**
     * Get last element
     * @return mixed
     */
    public function lastKey()
    {
        end($this);
        return key($this);
    }


    /**
     * Count all elements
     * @return int
     */
    public function count()
    {
        return count($this);
    }


    /**
     * Find element and return key
     * @param $value
     * @return mixed
     */
    public function find($value)
    {
        return array_search($value, $this);
    }


    /**
     * Get keys
     * @return array
     */
    public function keys()
    {
        return array_keys($this);
    }


    /**
     * Get values
     * @return array
     */
    public function values()
    {
        return array_values($this);
    }


    /**
     * Push element
     * @param $element
     * @return $this
     */
    public function push($element)
    {
        foreach(func_get_args() as $element) {
            array_push($this, $element);
        }
        return $this;
    }


    /**
     * Pop element
     * @return mixed
     */
    public function pop()
    {
        return array_pop($this);
    }


    /**
     * Unshift element
     * @param $element
     * @return $this
     */
    public function pushEnd($element)
    {
        foreach(func_get_args() as $element) {
            array_unshift($this, $element);
        }
        return $this;
    }


    /**
     * Shift element
     * @return mixed
     */
    public function popEnd()
    {
        return array_shift($this);
    }


    /**
     * Insert element
     * @param $element
     * @param $after
     * @return $this
     */
    public function insert($element, $after)
    {
        $before = array_slice($this, 0, $after);
        $after = array_slice($this, $after);
        $before[] = $element;
        $array = array_merge($before, array_values($after));
        $this->exchangeArray($array);
        return $this;
    }


    /**
     * Slice array in many part
     * @param $from
     * @param null $to
     * @return $this
     */
    public function slice($from, $to = null)
    {
        $array = array_slice($this, $from, $to, true);
        $this->exchangeArray($array);
        return $this;
    }


    /**
     * Divide array into small arrays
     * @param $size
     * @return array
     */
    public function split($size)
    {
        $chunks = array_chunk($this, $size, true);
        foreach($chunks as $key => $value) {
            $chunks[$key] = new self($value);
        }
        return $chunks;
    }


    /**
     * Apply a callback to all elements
     * @param callable $callback
     * @return $this
     */
    public function map(\Closure $callback)
    {
        // parse args
        $args = func_get_args();
        $callback = array_pop($args);
        array_push($args, $this);
        array_push($args, $callback);

        $array = call_user_func_array('array_map', $args);
        $this->exchangeArray($array);
        return $this;
    }


    /**
     * Remove element from callback
     * @param callable $callback
     * @return $this
     */
    public function filter(\Closure $callback)
    {
        $array = array_filter($this, $callback);
        $this->exchangeArray($array);
        return $this;
    }


    /**
     * Remove key from callback
     * @param callable $callback
     * @return $this
     */
    public function filterKey(\Closure $callback)
    {
        $array = array_flip($this);
        $array = array_filter($array, $callback);
        $array = array_flip($array);
        $this->exchangeArray($array);
        return $this;
    }


    /**
     * Get random key(s)
     * @param int $num
     * @return mixed
     */
    public function randKey($num = 1)
    {
        return array_rand($this, $num);
    }


    /**
     * Get random value
     * @param int $num
     * @return array
     */
    public function random($num = 1)
    {
        $rand = array_rand($this, $num);
        if(!is_array($rand)) {
            $rand = [$rand];
        }

        $values = new self();
        foreach($rand as $key => $index) {
            $values[$index] = $this[$index];
        }

        return $values;
    }


    /**
     * Mix randomly elements
     * @return mixed
     */
    public function shuffle()
    {
        array_shift($this);
        return $this;
    }


    /**
     * Reverse rows
     * @return $this
     */
    public function reverse()
    {
        $array = array_reverse($this, true);
        $this->exchangeArray($array);
        return $this;
    }


    /**
     * Merge with other arrays
     * @param array $array
     * @return $this
     */
    public function merge(array $array)
    {
        $array = array_merge($this, func_get_args());
        $this->exchangeArray($array);
        return $this;
    }


    /**
     * Get column
     * @param $column
     */
    public function column($column)
    {
        // todo
    }


    /**
     * Sort array by column names and directions
     * Ex : $sorted = $array->sort(['name' => SORT_DESC]);
     * @param array $by
     * @return mixed
     */
    public function sort(array $by)
    {
        // init
        $columns = [];
        foreach($by as $column => $dir) {
            $columns[$column] = [];
        }

        // sort
        foreach($this as $key => $line) {
            foreach($line as $column => $value) {

                // apply sort ?
                if(isset($by[$column])) {
                    $columns[$column][$key] = $value;
                }

            }
        }

        // create full args
        $args = [];
        foreach($columns as $name => $data) {
            $args[] = $data;        // column name
            $args[] = $by[$name];  // direction
        }
        $args[] = $this;

        // apply multisort
        $ouput = call_user_func_array('array_multisort', $args);

        return new self($ouput);
    }

}