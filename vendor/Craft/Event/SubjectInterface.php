<?php

namespace Craft\Event;

interface SubjectInterface
{

    /**
     * Attach callback
     * @param string $event
     * @param callable $callback
     */
    public function on($event, callable $callback);

    /**
     * Attach listener
     * @param Listener $listener
     */
    public function attach(Listener $listener);

    /**
     * Detach all event listeners
     * @param $event
     * @return mixed
     */
    public function off($event);

    /**
     * Event event
     * @param string $event
     * @param array $params
     * @return int
     */
    public function fire($event, array $params = []);

} 