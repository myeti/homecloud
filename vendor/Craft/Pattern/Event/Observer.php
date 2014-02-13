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

abstract class Observer
{

    /**
     * Get notification from event subject
     * @param string $event
     * @param array $params
     * @return mixed
     */
    abstract public function notify($event, array $params = []);

} 