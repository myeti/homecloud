<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Cli;

abstract class Command
{

    /** @var array */
    protected $arguments = [];

    /** @var array */
    protected $parameters = [];

    /** @var array */
    protected $flags = [];

    /** @var string */
    protected $description;


    /**
     * Setup command
     * @param $description
     */
    public function __construct($description)
    {
        $this->description = $description;
        $this->register();
    }


    /**
     * Add required argument
     * @param $name
     * @param $description
     * @return $this
     */
    protected function argument($name, $description)
    {
        $this->arguments[$name] = $description;
        return $this;
    }


    /**
     * Add optional param starting with -
     * @param $name
     * @param $description
     * @return $this
     */
    protected function param($name, $description)
    {
        $this->parameters[$name] = $description;
        return $this;
    }


    /**
     * Add option flag starting with --
     * @param $name
     * @param $description
     * @return $this
     */
    protected function flag($name, $description)
    {
        $this->flags[$name] = $description;
        return $this;
    }


    /**
     * Run command
     * @param array $args
     * @return string
     */
    public function run(array $args)
    {
        // init
        $arguments = array_fill_keys(array_keys($this->arguments), null);
        $parameters = array_fill_keys(array_keys($this->parameters), null);
        $flags = array_fill_keys(array_keys($this->flags), null);

        // parse args
        $skip = [];
        foreach($args as $k => $argument) {

            // skip argument
            if(in_array($k, $skip)) {
                continue;
            }

            // flag
            if(substr($argument, 0, 2) == '--') {

                // parse flag
                $flag = substr($argument, 2);

                // error
                if(!isset($flags[$flag])) {
                    return 'Unknown flag "' . $flag . '".';
                }

                $flags[$flag] = true;
            }
            // parameter
            elseif(substr($argument, 0, 1) == '-') {

                // parse parameter
                $parameter = substr($argument, 1);

                // error
                if(!isset($parameters[$parameter])) {
                    return 'Unknown parameter "' . $parameter . '".';
                }
                elseif(!isset($args[$k + 1])) {
                    return 'Parameter "' . $parameter . '" must have a value.';
                }

                // get value
                $value = $args[$k + 1];
                $skip[] = $k + 1;

                $parameters[$parameter] = $value;

            }
            // argument
            else {

                // parse argument
                list($argument, $value) = explode(' ', $argument);

                // error
                if(!isset($arguments[$argument])) {
                    return 'Unknown argument "' . $argument . '".';
                }
                elseif(!$value) {
                    return 'Argument "' . $argument . '" must have a value.';
                }

                $arguments[$argument] = $value;

            }

            // argument missing ?
            foreach($arguments as $name => $val) {
                if(!$val) {
                    return 'Argument "' . $name . '" is missing.';
                }
            }

            // execute command
            $this->execute($arguments, $parameters, $flags);
        }
    }


    /**
     * Register arguments
     * @return mixed
     */
    abstract protected function register();


    /**
     * Execute command
     * @param array $arguments
     * @param array $parameters
     * @param array $flags
     * @return mixed
     */
    abstract public function execute(array $arguments, array $parameters, array $flags);

}