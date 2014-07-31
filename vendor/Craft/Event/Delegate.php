<?php

namespace Craft\Event;

trait Delegate
{

    /** @var SubjectInterface */
    protected $subject;


    /**
     * Attach callback
     * @param string $event
     * @param callable $callback
     * @return $this
     */
    public function on($event, callable $callback)
    {
        $this->subject->on($event, $callback);
        return $this;
    }


    /**
     * Attach callback
     * @param Listener $listener
     * @return $this
     */
    public function attach(Listener $listener)
    {
        $this->subject->attach($listener);
        return $this;
    }


    /**
     * Detach all event listeners
     * @param $event
     * @return $this
     */
    public function off($event)
    {
        $this->subject->off($event);
        return $this;
    }


    /**
     * Event event
     * @param string $event
     * @param array $params
     * @return int
     */
    public function fire($event, array $params = [])
    {
        return $this->subject->fire($event, $params);
    }

} 