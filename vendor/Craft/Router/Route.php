<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Router;

class Route
{

    /** @var string */
    public $name;

    /** @var string */
    public $path;

    /** @var mixed */
    public $target;

    /** @var array */
    public $context = [];

    /** @var array */
    public $data = [];


    /**
     * Setup route
     * @param string $name
     * @param string $path
     * @param mixed $target
     * @param array $context
     */
    public function __construct($name, $path, $target, array $context = [])
    {
        $this->name = $name;
        $this->path = $path;
        $this->target = $target;
        $this->context = $context;
    }

} 