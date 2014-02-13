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

use Craft\Data\Provider;

class RouteProvider implements Provider
{

    /** @var Route[] */
    protected $routes = [];


    /**
     * Setup with initial routes
     * @param array $routes
     */
    public function __construct(array $routes = [])
    {
        foreach($routes as $name => $route) {
            $this->set($name, $route);
        }
    }


    /**
     * Check if element exists
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->routes[$name]);
    }


    /**
     * Get element by key, fallback on error
     * @param string $name
     * @param null $fallback
     * @return mixed
     */
    public function get($name, $fallback = null)
    {
        return isset($this->routes[$name]) ? $this->routes[$name] : $fallback;
    }


    /**
     * Drop element by key
     * @param string $name
     * @return bool
     */
    public function drop($name)
    {
        unset($this->routes[$name]);
    }


    /**
     * Set element by key with value
     * @param $name
     * @param $route
     * @param array $context
     * @return bool
     */
    public function set($name, $route, array $context = [])
    {
        if(is_array($name)) {
            foreach($name as $subname) {
                $this->set($subname, $route, $context);
            }
        }
        elseif(!$route instanceof Route) {
            $route = new Route($name, $name, $route, $context);
        }

        $this->setRoute($name, $route);
    }


    /**
     * Set route
     * @param $name
     * @param Route $route
     */
    protected function setRoute($name, Route $route)
    {
        $this->routes[$name] = $route;
    }


    /**
     * Get all routes
     * @return array
     */
    public function all()
    {
        return $this->routes;
    }

}