<?php

namespace Craft\Web;

use Forge\Engine;

class Form implements \IteratorAggregate
{

    /** @var string */
    public $action;

    /** @var string */
    public $method;

    /** @var Form\Field[] */
    protected $fields = [];

    /**
     * Create empty form
     * @param string $action
     * @param string $method
     */
    public function __construct($action, $method = 'post')
    {
        $this->action = $action;
        $this->method = $method;
        $this->setup();
    }


    /**
     * Custom init
     */
    public function setup(){}


    /**
     * Add field to form
     * @param Form\Field $field
     * @return $this
     */
    public function add(Form\Field $field)
    {
        $this->fields[$field->name] = $field;
        return $this;
    }


    /**
     * Fill values
     * @param array $data
     */
    public function fill(array $data)
    {
        foreach($data as $name => $value) {
            if(isset($this->fields[$name])) {
                $this->fields[$name]->value = $value;
            }
        }
    }


    /**
     * Retrieve an external iterator
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->fields);
    }


    /**
     * Open tag
     * @return string
     */
    public function open()
    {
        return Engine::render(__DIR__ . '/Form/templates/form.open', ['form' => $this]);
    }


    /**
     * Close tag
     * @return string
     */
    public function close()
    {
        return Engine::render(__DIR__ . '/Form/templates/form.close');
    }


    /**
     * Print form
     * @return string
     */
    public function __toString()
    {
        return Engine::render(__DIR__ . '/Form/templates/form', ['form' => $this]);
    }

}