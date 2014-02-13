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

use Craft\Router\Matcher\UrlMatcher;
use Craft\Router\RouteProvider;

class Console
{

    /** @var In */
    protected $in;

    /** @var Out */
    protected $out;

	/** @var array */
	protected $commands = [];


    /**
     * Define i&o
     * @param Out $out
     * @param In $in
     */
    public function __construct(Out $out = null, In $in = null)
    {
        // define i&o
        $this->in = $in ?: new In();
        $this->out = $out ?: new Out();

        // default welcome
        $this->command('cli.welcome', function(){
            $this->out->say('Welcome :)')->nl();
        });

        // default not found
        $this->command('cli.notfound', function($command){
            $this->out->say('Command [' . $command . '] not found.')->nl();
        });
    }


    /**
     * Register command
     * @param $command
     * @param \Closure $callback
     * @internal param string $name
     */
	public function command($command, \Closure $callback)
	{
		$this->commands[$command] = $callback;
	}


	/**
	 * Let's go !
	 */
	public function plug()
	{
		// make command
		$query = $_SERVER['argv'];
		array_shift($query);
        $query = implode(' ', $query);

        // welcome
        if(empty($query)) {
            call_user_func($this->commands['cli.welcome'], $query);
            exit;
        }

        // setup router
        $matcher = new UrlMatcher(new RouteProvider($this->commands));

        // look up command
        $route = $matcher->find($query);

        // error
        if(!$route) {
            call_user_func($this->commands['cli.notfound'], $query);
            exit;
        }

        // execute
        return call_user_func_array($route->target, $route->data);
	}

}