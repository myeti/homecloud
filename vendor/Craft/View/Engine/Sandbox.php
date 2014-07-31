<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\View\Engine;

use Craft\View\Engine;

abstract class Sandbox
{

    /** @var Engine */
    private $engine;

    /** @var string */
    private $template;

    /** @var string */
    private $layout;

    /** @var array */
    private $data = [];

    /** @var String[] */
    private $sections = [];

    /** @var string */
    private $section;

    /** @var array */
    private $helpers = [];

    /** @var bool */
    private $rendering = false;


    /**
     * Init template
     * @param Engine $engine
     * @param string $template
     * @param array $data
     * @param array $sections
     * @param array $helpers
     */
    public function __construct(Engine $engine, $template, array $data = [], array $sections = [], array $helpers = [])
    {
        $this->engine = $engine;
        $this->template = $template;
        $this->data = $data;
        $this->sections = $sections;
        $this->helpers = $helpers;
    }


    /**
     * Set layout
     * @param $template
     * @param array $data
     * @return string
     */
    protected function layout($template, array $data = [])
    {
        $this->layout = [$template, $data];
    }


    /**
     * Start recording section
     * @param $name
     */
    protected function section($name)
    {
        $this->section = $name;
        ob_start();
    }


    /**
     * Stop recording section
     */
    protected function end()
    {
        $this->sections[$this->section] = ob_get_clean();
        $this->section = null;
    }


    /**
     * Insert section
     * @param $section
     * @return string
     */
    protected function block($section)
    {
        return isset($this->sections[$section]) ? $this->sections[$section] : null;
    }


    /**
     * Insert child content
     * @return string
     */
    protected function content()
    {
        return $this->block('__content__');
    }


    /**
     * Load partial
     * @param $template
     * @param array $data
     * @param array $sections
     * @return string
     */
    protected function load($template, array $data = [], array $sections = [])
    {
        return $this->engine->render($template, $data, $sections);
    }


    /**
     * Call helper
     * @param string $helper
     * @param array $args
     * @throws \LogicException
     * @return mixed
     */
    public function __call($helper, array $args = [])
    {
        if(isset($this->helpers[$helper])) {
            return call_user_func_array($this->helpers[$helper], $args);
        }

        throw new \LogicException('Unknown helper "' . $helper . '".');
    }


    /**
     * Render template
     * @throws \LogicException
     * @return string
     */
    public function compile()
    {
        // start rendering
        if($this->rendering) {
            throw new \LogicException('Template is already rendering.');
        }
        $this->rendering = true;

        // compile
        extract($this->data);
        ob_start();
        require $this->template;
        $content = ob_get_clean();

        // update layout
        if($this->layout) {
            $this->layout[] = array_merge(
                $this->sections,
                ['__content__' => $content]
            );
        }

        // end
        $this->rendering = false;

        return $content;
    }


    /**
     * Get parent layout
     * @return array
     */
    public function parent()
    {
        return $this->layout;
    }

} 