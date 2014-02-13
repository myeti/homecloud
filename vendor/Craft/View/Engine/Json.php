<?php

namespace Craft\View\Engine;

use Craft\View\Engine;

class Json implements Engine
{

    /** @var \Closure */
    protected $wrapper;


    /**
     * Setup json with wrapper
     * @param callable $wrapper
     */
    public function __construct(\Closure $wrapper = null)
    {
        $this->wrapper = $wrapper;
    }


    /**
     * Render view
     * @param null|\Closure $wrapper
     * @param array $data
     * @return string
     */
    public function render($wrapper = null, array $data = [])
    {
        $wrapper = $wrapper ?: $this->wrapper;
        if($wrapper) {
            $data = call_user_func($this->wrapper, $data);
        }

        return json_encode($data);
    }

} 