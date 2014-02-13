<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Debug\Logger;

class Log
{

    /** @var string */
    public $time;

    /** @var string */
    public $level;

    /** @var string */
    public $content;

    /**
     * Create new log
     * @param $level
     * @param $content
     */
    public function __construct($level, $content)
    {
        $this->time = microtime(true);
        $this->level = $level;
        $this->content = $content;
    }

    /**
     * Format log message
     * @return string
     */
    public function __toString()
    {
        list($time, $micro) = explode('.', $this->time);
        $date = date('Y-m-d H:i:s.', $time) . str_pad($micro, 4, 0);
        return '[' . $date . '] ' . $this->level . ' - ' . $this->content;
    }

} 