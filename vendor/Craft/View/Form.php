<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\View;

use Craft\View\Engine\Native;
use Craft\View\Form\Element;
use Craft\View\Form\Field;

class Form extends \ArrayObject implements Element
{

    /** @var string */
    public $name;

    /** @var string */
    public $url = '#';

    /** @var string */
    public $method = 'post';

    /** @var string */
    public $parent;

    /** @var string */
    public $template = 'form';


    /**
     * Setup form
     * @param string $name
     * @param string $url
     * @param string $method
     * @param string $parent
     */
    public function __construct($name, $url = '#', $method = 'post', $parent = null)
    {
        $this->name = $name;
        $this->url = $url;
        $this->method = $method;
        $this->parent = $parent;

        parent::__construct();
    }


    /**
     * Get inner name
     * @return string
     */
    public function name()
    {
        return $this->parent ? $this->parent . '[' . $this->name . ']' : $this->name;
    }


    /**
     * Add field
     * @param Form\Element $element
     * @return Element
     */
    public function &add(Element $element)
    {
        $element->parent = $this->name();
        parent::offsetSet($element->name, $element);
        return $element;
    }


    /**
     * Nope !
     * @param mixed $a
     * @param mixed $b
     */
    public function offsetSet($a, $b)
    {
        // nope
    }


    /**
     * Return template to use
     * @return string
     */
    public function html()
    {
        if($this->parent) {
            $this->template .= '.sub';
        }

        $engine = new Native(__DIR__ . '/Form/templates/', 'php');
        return $engine->render($this->template, ['form' => $this]);
    }

}