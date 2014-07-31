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

class Repository extends \ArrayObject implements ProviderInterface
{

    /** @var string */
    protected $separator = '.';


    /**
     * Setup array and separator
     * @param array $input
     * @param string $separator
     */
    public function __construct(array $input = [], $separator = '.')
    {
        // set separator
        $this->separator = $separator;

        // init array
        parent::__construct($input);
    }


    /**
     * Get all elements
     * @return array
     */
    public function all()
    {
        return $this->getArrayCopy();
    }


    /**
     * Check if element exists
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        // get data
        $array = $this->resolve($key);
        $key = $this->parse($key);

        return isset($array[$key]);
    }


    /**
     * Get element by key, fallback on error
     * @param $key
     * @param null $fallback
     * @return mixed
     */
    public function get($key, $fallback = null)
    {
        // get data
        $array = $this->resolve($key);
        $key = $this->parse($key);
        $value = isset($array[$key]) ? $array[$key] : $fallback;

        return $value;
    }


    /**
     * Set element by key with value
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value)
    {
        // get data
        $array = &$this->resolve($key, true);
        $key = $this->parse($key);

        // write
        $array[$key] = $value;
    }


    /**
     * Drop element by key
     * @param $key
     * @return bool
     */
    public function drop($key)
    {
        // get data
        $array = &$this->resolve($key);
        $key = $this->parse($key);

        // drop
        if(isset($array[$key])) {
            unset($array[$key]);
        }
    }


    /**
     * Clear all elements
     * @return bool
     */
    public function clear()
    {
        $this->exchangeArray([]);
    }


    /**
     * Resolve path to value
     * @param string $namespace
     * @param bool $dig
     * @return array
     */
    protected function &resolve($namespace, $dig = false)
    {
        // parse info
        $array = &$this;
        $namespace = trim($namespace, $this->separator);
        $segments = explode($this->separator, $namespace);
        $last = end($segments);

        // one does not simply walk into Mordor
        foreach($segments as $i => $segment) {

            // is last ?
            if($segment == $last) {
                break;
            }

            // namespace does not exist
            if(!isset($array[$segment])) {

                // stop here
                if(!$dig) {
                    break;
                }

                $array[$segment] = [];

            }

            // next segment
            $array = &$array[$segment];

        }

        return $array;
    }


    /**
     * Parse key
     * @param $namespace
     * @return mixed
     */
    protected function parse($namespace)
    {
        $segments = explode($this->separator, $namespace);
        return end($segments);
    }

}