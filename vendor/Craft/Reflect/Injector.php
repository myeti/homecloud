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

class Injector
{

    /** @var array */
    protected $params = [];

    /** @var array */
    protected $shared = [];

    /** @var array */
    protected $factories = [];


    /**
     * Setup with params
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        foreach($params as $class => $param) {
            $this->params[$class] = $param;
        }
    }


    /**
     * Check if injector has definition
     * @param $class
     * @return bool
     */
    public function has($class)
    {
        return isset($this->shared[$class])
            ?: isset($this->factories[$class])
            ?: isset($this->params[$class]);
    }


    /**
     * Define params for class instance
     * @param $class
     * @param array $params
     * @return $this
     */
    public function define($class, array $params = [])
    {
        $class = is_object($class) ? get_class($class) : $class;
        $this->params[$class] = $params;

        return $this;
    }


    /**
     * Set user factory
     * @param $class
     * @param callable $factory
     * @return $this
     */
    public function factory($class, callable $factory)
    {
        $class = is_object($class) ? get_class($class) : $class;
        $this->factories[$class] = $factory;

        return $this;
    }


    /**
     * Define singleton
     * @param $class
     * @param array $params
     * @return $this
     */
    public function share($class, array $params = [])
    {
        $classname = is_object($class) ? get_class($class) : $class;
        $this->define($classname, $params);
        $this->shared[$classname] = function(array $params = []) use($class)
        {
            static $instance;
            if(!$instance) {
                $instance = is_object($class)
                    ? $class
                    : $this->resolve($class, $params);
            }

            return $instance;
        };

        return $this;
    }


    /**
     * Remove singleton
     * @param $class
     * @return $this
     */
    public function unshare($class)
    {
        unset($this->shared[$class]);

        return $this;
    }


    /**
     * Make class instance
     * @param $class
     * @param array $params
     * @return null
     */
    public function make($class, array $params = [])
    {
        // define params
        if(!$params and isset($this->params[$class])) {
            $params = $this->params[$class];
        }

        // is shared
        if(isset($this->shared[$class])) {

            // already object
            if(is_object($this->shared[$class])) {
                return call_user_func_array($this->shared[$class], $params);
            }

            // resolve
            return $this->resolve($class, $params);
        }

        // has factory
        if(isset($this->factories[$class])) {
            return call_user_func_array($this->factories[$class], $params);
        }

        return $this->resolve($class, $params);
    }


    /**
     * Resolve instance
     * @param $class
     * @param array $params
     * @return null
     */
    protected function resolve($class, array $params = [])
    {
        // already object
        if(is_object($class)) {
            return $class;
        }

        // scalar value
        if(!class_exists($class)) {
            return $class;
        }

        // get reflection
        $reflector = new \ReflectionClass($class);
        $constructor = $reflector->getConstructor();

        // resolve params
        $args = [];
        if($constructor) {
            foreach($constructor->getParameters() as $parameter) {

                // define name
                $name = $parameter->getName();

                // user defined
                if(isset($params[$name])) {
                    $args[$name] = $this->make($params[$name]);
                }
                // type hint
                elseif($hint = $parameter->getClass()) {
                    $args[$name] = $this->make($hint->getName());
                }
                // default value
                else {
                    $args[$name] = $parameter->getDefaultValue();
                }

            }
        }

        // run constructor
        return $reflector->newInstanceArgs($args);
    }

} 