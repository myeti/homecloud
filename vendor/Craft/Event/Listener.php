<?php

namespace Craft\Event;

interface Listener
{

    /**
     * Subscribe to subject's events
     * @param SubjectInterface $subject
     */
    public function subscribe(SubjectInterface $subject);

} 