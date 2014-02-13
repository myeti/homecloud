<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Pattern\Event;

trait Subject
{

    /** @var array */
    protected $events = [];


    /**
     * Attach callback
     * @param $name
     * @param \Closure|Observer $listener
     * @throws \InvalidArgumentException
     */
    public function on($name, $listener)
    {
        // type hint
        if(!($listener instanceof \Closure) and !($listener instanceof Observer)) {
            throw new \InvalidArgumentException('Event subject must attach Closure or Observer object.');
        }

        // create event repository
        if(!isset($this->events[$name])){
            $this->events[$name] = [];
        }

        // attach callback
        $this->events[$name][] = $listener;
    }


    /**
     * Fire event
     * @param $name
     * @param array $params
     * @internal param array $args
     */
    public function fire($name, array $params = [])
    {
        // fire * event
        if($name != '*') {
            $this->fire('*', ['name' => $name, 'params' => $params]);
        }

        // no listeners
        if(empty($this->events[$name])) {
            return;
        }

        // trigger all listeners
        foreach($this->events[$name] as $listener){

            // callback
            if($listener instanceof \Closure) {
                call_user_func_array($listener, [$name, $params]);
            }
            // observer
            elseif($listener instanceof Observer) {
                $listener->notify($name, $params);
            }

        }
    }


}