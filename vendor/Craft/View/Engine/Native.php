<?php

namespace Craft\View\Engine;

use Craft\View\Engine;

class Native extends \ArrayObject implements Engine
{

    /** @var string */
    protected $root;

    /** @var string */
    protected $ext;

    /** @var Native\Helper[] */
    protected $helpers = [];


    /**
     * Setup root path
     * @param string $root
     * @param string $ext
     */
    public function __construct($root = null, $ext = null)
    {
        $this->root = rtrim($root, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->ext = '.' . ltrim($ext, '.');

        // default helpers
        $this->mount(new Native\Helper\Asset());
        $this->mount(new Native\Helper\Html());
        $this->helper('partial', [$this, 'render']);

        parent::__construct();
    }


    /**
     * Add helper function
     * @param string $name
     * @param callable $helper
     * @return $this
     */
    public function helper($name, callable $helper)
    {
        $this->helpers[$name] = $helper;
        return $this;
    }


    /**
     * Mount helper object
     * @param Native\Helper $helper
     * @return $this
     */
    public function mount(Native\Helper $helper)
    {
        foreach($helper->register() as $name => $helper) {
            $this->helper($name, $helper);
        }
        return $this;
    }


    /**
     * Render data with resource
     * @param $template
     * @param array $data
     * @param array $sections
     * @return string
     */
    public function render($template, array $data = [], array $sections = [])
    {
        // define data
        $template = $this->root . $template . $this->ext;
        $data = array_merge((array)$this, $data);

        // create template
        $template = new Native\Template($template, $data, $sections, $this->helpers);

        // compile
        $content = $template->compile();

        // layout ?
        if($parent = $template->parent()) {

            // extract layout data
            list($layout, $data, $sections) = $parent;

            // define data
            $data = array_merge((array)$this, $data);

            // render layout
            $content = $this->render($layout, $data, $sections);

        }

        return $content;
    }

}