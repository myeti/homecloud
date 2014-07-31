<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Trace;

use Forge\Mog;

class Log
{

    /** @var float */
    public $time;

    /** @var float */
    public $elapsed;

    /** @var string */
    public $level;

    /** @var string */
    public $content;

    /** @var array */
    public $context = [];


    /**
     * Create new log
     * @param int $level
     * @param string $content
     * @param string $context
     */
    public function __construct($level, $content, $context)
    {
        $this->time = microtime(true);
        $this->elapsed = Mog::elapsed();
        $this->level = $level;
        $this->content = $content;
        $this->context = $context;
    }

} 