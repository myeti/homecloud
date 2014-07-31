<?php

namespace Craft\Reflect;

interface InjectorInterface
{

    /**
     * Check if injector has definition
     * @param $class
     * @return bool
     */
    public function has($class);


    /**
     * Define params for class instance
     * @param $class
     * @param array $params
     * @return $this
     */
    public function define($class, array $params = []);


    /**
     * Set user factory
     * @param $class
     * @param callable $factory
     * @return $this
     */
    public function factory($class, callable $factory);


    /**
     * Define singleton
     * @param $class
     * @param array $params
     * @return $this
     */
    public function share($class, array $params = []);


    /**
     * Remove singleton
     * @param $class
     * @return $this
     */
    public function unshare($class);


    /**
     * Make class instance
     * @param $class
     * @param array $params
     * @return null
     */
    public function make($class, array $params = []);

} 