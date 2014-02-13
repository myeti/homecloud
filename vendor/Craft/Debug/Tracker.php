<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Debug;

use Craft\Debug\Tracker\Task;

class Tracker
{

    /** @var Task[] */
    public $tasks = [];


    /**
     * Start task
     * @param string $task
     */
    public function start($task)
    {
        $this->tasks[$task] = new Task($task);
    }


    /**
     * Add tag
     * @param string $task
     * @param string $tag
     * @return bool|array
     */
    public function tag($task, $tag)
    {
        return $this->tasks[$task]->tag($tag);
    }


    /**
     * Stop task
     * @param string $task
     * @return array
     */
    public function stop($task)
    {
        return $this->tasks[$task]->stop();
    }


    /**
     * Get task report
     * @param string $task
     * @return array
     */
    public function report($task)
    {
        return $this->tasks[$task]->report();
    }


    /**
     * Get task
     * @param $task
     * @return Task
     */
    public function task($task)
    {
        return $this->tasks[$task];
    }

} 