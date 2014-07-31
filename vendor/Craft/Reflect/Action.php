<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Reflect;

class Action
{

    /** @var callable */
    public $callable;

    /** @var array */
    public $meta = [];

    /** @var array */
    public $args = [];

    /** @var mixed */
    public $data;

    /** @var string */
    public $type;


    /**
     * Init action
     * @param callable $callable
     * @param array $args
     * @param array $meta
     * @param mixed $data
     */
    public function __construct(callable $callable, array $meta = [], array $args = [], $data = null)
    {
        $this->callable = $callable;
        $this->args = $args;
        $this->meta = $meta;
        $this->data = $data;
        $this->type = 'callable';
    }


    /**
     * Execute action
     * @return mixed
     */
    public function __invoke()
    {
        $args = func_get_args() ?: $this->args; // args take over
        return $this->data = call_user_func_array($this->callable, $args);
    }

} 