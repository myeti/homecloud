<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Debug\Tracker;

class Task extends \ArrayObject
{

    /** @var string */
    public $name;

    /** @var float */
    public $ref;

    /** @var bool */
    public $stopped = false;


    /**
     * Start task tracking
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->ref = microtime(true);
        $this->tag('start');
    }


    /**
     * Tag task if not stopped
     * @param $name
     * @return array|bool
     */
    public function tag($name)
    {
        if(!$this->stopped) {
            $time = microtime(true) - $this->ref;
            $memory = memory_get_usage(true);
            return $this[$name] = [$time, $memory];
        }

        return false;
    }


    /**
     * Stop task tracking
     * return array|bool
     */
    public function stop()
    {
        $report = $this->tag('stop');
        $this->stopped = true;
        return $report;
    }


    /**
     * Formatted report
     * @param bool $unit
     * @return array
     */
    public function report($unit = true)
    {
        // parse data
        list($time, $memory) = end($this);
        $time = number_format($time, 4);
        $memory = ($memory / 1024);

        // add unit
        if($unit) {
            $time .= 'ms';
            $memory .= 'kb';
        }

        return [$time, $memory];
    }

} 