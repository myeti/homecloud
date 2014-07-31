<?php

namespace Craft\Web\Form;

use Forge\Engine;

abstract class Field
{

    /** @var string */
    public $name;

    /** @var string */
    public $value;

    /** @var string */
    public $label;

    /** @var string */
    public $placeholder;

    /** @var string */
    public $helper;

    /** @var string */
    public $id;


    /**
     * Init field
     * @param string $name
     * @param string $value
     * @param array $texts
     */
    public function __construct($name, $value = null, array $texts = [])
    {
        $this->name = $name;
        $this->value = $value;

        $texts += [
            'label'         => null,
            'placeholder'   => null,
            'helper'        => null,
            'id'            => $name . '_' . uniqid()
        ];

        $this->label = $texts['label'];
        $this->placeholder = $texts['placeholder'];
        $this->helper = $texts['helper'];
    }


    /**
     * Render label
     * @return string
     */
    public function label()
    {
        return Engine::render(__DIR__ . '/templates/default.label', ['field' => $this]);
    }


    /**
     * Render input
     * @return string
     */
    public function input()
    {
        return Engine::render(__DIR__ . '/templates/default.input', ['field' => $this]);
    }


    /**
     * Render helper
     * @return string
     */
    public function helper()
    {
        return Engine::render(__DIR__ . '/templates/default.helper', ['field' => $this]);
    }


    /**
     * Render field
     * @return string
     */
    public function __toString()
    {
        return Engine::render(__DIR__ . '/templates/default', ['field' => $this]);
    }

} 