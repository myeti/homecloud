<?php

namespace Forge;

use Craft\View\Engine as HtmlEngine;
use Craft\View\Helper;

abstract class Engine
{

    /**
     * Get engine instance
     * @return HtmlEngine
     */
    protected static function instance()
    {
        static $instance;
        if(!$instance) {
            $instance = new HtmlEngine;
        }

        return $instance;
    }


    /**
     * Add helper function
     * @param string $name
     * @param callable $helper
     * @return $this
     */
    public static function helper($name, callable $helper)
    {
        return static::instance()->helper($name, $helper);
    }

    /**
     * Mount helper object
     * @param Helper $helper
     * @return $this
     */
    public static function mount(Helper $helper)
    {
        return static::instance()->mount($helper);
    }


    /**
     * Render data with resource
     * @param $template
     * @param array $data
     * @param array $sections
     * @return string
     */
    public static function render($template, $data = [], array $sections = [])
    {
        return static::instance()->render($template, $data, $sections);
    }

} 