<?php

namespace Craft\Map;

use Craft\Reflect\Annotation;
use Craft\Storage\Finder\GlobFinder;

class Router implements RouterInterface
{

    /** @var Route[] */
    protected $routes = [];

    /** @var array */
    protected $prefix = [];

    /** @var callable[] */
    protected $before = [];

    /** @var callable[] */
    protected $after = [];

    /** @var array */
    protected $verbs = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'HEAD'];


    /**
     * Setup matcher
     * @param array $routes
     */
    public function __construct(array $routes = [])
    {
        foreach($routes as $from => $action) {
            if($action instanceof Route) {
                $this->add($action);
            }
            else {
                $this->map($from, $action);
            }
        }
    }


    /**
     * Make route from path
     * @param string $from
     * @param mixed $action
     * @param array $context
     * @param callable $before
     * @param callable $after
     * @return $this
     */
    public function map($from, $action, array $context = [], callable $before = null, callable $after = null)
    {
        $route = new Route($from, $action, $context);

        if($before) {
            $route->before($before);
        }

        if($after) {
            $route->after($after);
        }

        return $this->add($route);
    }


    /**
     * Add route
     * @param Route $route
     * @return $this
     */
    public function add(Route $route)
    {
        // add prefixes
        $route->from = implode(null, $this->prefix) . $route->from;

        // add before callbacks
        foreach($this->before as $before) {
            $route->before($before);
        }

        // add after callbacks
        foreach($this->after as $after) {
            $route->after($after);
        }

        $this->routes[$route->from] = $route;
        return $this;
    }


    /**
     * Group routes
     * @param string $base
     * @param callable $group
     * @param callable $before
     * @param callable $after
     * @return $this
     */
    public function group($base, callable $group, callable $before = null, callable $after = null)
    {
        // add grouped prefix and callbacks
        array_push($this->prefix, $base);
        if($before){
            array_push($this->before, $before);
        }
        if($after){
            array_push($this->after, $after);
        }

        // execute group
        call_user_func($group, $this);

        // add grouped prefix and callbacks
        array_pop($this->prefix);
        if($before){
            array_pop($this->before);
        }
        if($after){
            array_pop($this->after);
        }

        return $this;
    }


    /**
     * Get route
     * @param $path
     * @return Route
     */
    public function route($path)
    {
        return isset($this->routes[$path])
            ? $this->routes[$path]
            : false;
    }


    /**
     * Get all routes
     * @return Route[]
     */
    public function routes()
    {
        return $this->routes;
    }


    /**
     * Find route
     * @param string $query
     * @param array $context
     * @return Route
     */
    public function find($query, array $context = [])
    {
        // prepare query
        list($query, $context) = $this->prepare($query, $context);

        // search in all routes
        foreach($this->routes as $route)
        {
            // route filter
            $route = $this->filter($route);

            // compile pattern
            $pattern = $this->compile($route->from);

            // compare
            if(preg_match($pattern, $query, $data)){

                // check context
                if(!$this->check($route, $context)) {
                    continue;
                }

                // parse data
                unset($data[0]);
                $route = $this->parse($route, $data);

                return $route;
            }
        }

        return false;
    }


    /**
     * Find route
     * @param string $query
     * @param array $context
     * @return string
     */
    protected function prepare($query, array $context = [])
    {
        // resolve path
        list($verb, $query) = $this->resolve($query);

        // update context
        if($verb) {
            $context['method'] = $verb;
        }

        // clean query
        $query = '/' . trim($query, '/');

        return [$query, $context];
    }


    /**
     * Filter route
     * @param Route $route
     * @return Route
     */
    protected function filter(Route $route)
    {
        // resolve path
        list($verb, $query) = $this->resolve($route->from);

        // update context
        if($verb) {
            $route->context['method'] = $verb;
        }

        // clean query
        $route->from =  '/' . trim($query, '/');

        return $route;
    }


    /**
     * Compile path into regex
     * @param $path
     * @return mixed|string
     */
    protected function compile($path)
    {
        $pattern = str_replace('/', '\/', $path);
        $pattern = preg_replace('#\:(\w+)#', '(?P<data__$1>(.+))', $pattern);
        $pattern = preg_replace('#\+(\w+)#', '(?P<meta__$1>(.+))', $pattern);
        $pattern = '#^' . $pattern . '$#';

        return $pattern;
    }


    /**
     * Check context
     * @param Route $route
     * @param array $context
     * @return bool
     */
    protected function check(Route $route, array $context = [])
    {
        return (array_intersect_assoc($context, $route->context) == $route->context);
    }


    /**
     * Parse results
     * @param Route $route
     * @param array $data
     * @return array
     */
    protected function parse(Route $route, array $data)
    {
        // default values
        $parsed = [
            'data' => [],
            'meta' => []
        ];

        // parse
        foreach($data as $key => $value) {
            if(substr($key, 0, 6) == 'data__' or substr($key, 0, 6) == 'meta__') {
                $group = substr($key, 0, 4);
                $label = substr($key, 6);
                $parsed[$group][$label] = $value;
            }
        }

        // update route
        $route->data = $parsed['data'];
        $route->meta = $parsed['meta'];

        return $route;
    }


    /**
     * Resolve query
     * @param $query
     * @return array
     */
    protected function resolve($query)
    {
        // has http verb
        if($query and strpos(' ', $query) !== false) {

            // get verb
            list($verb, $path) = explode(' ', $query);
            $verb = strtoupper($verb);

            // return resolved
            if(in_array($verb, $this->verbs)) {
                return [$verb, $path];
            }

        }

        return [null, $query];
    }


    /**
     * Setup routes from methods
     * @param array $classes
     * @throws \InvalidArgumentException
     * @return Router
     */
    public static function actions(array $classes)
    {
        $routes = [];

        // for all classes
        foreach($classes as $class) {

            // not class
            if(!class_exists($class)) {
                throw new \InvalidArgumentException('Class "' . $class . '" not found.');
            }

            // scan class
            $ref = new \ReflectionClass($class);

            // make query
            $query = '/' . strtolower($ref->getShortName());

            // get methods
            foreach($ref->getMethods() as $method) {

                // add action in query
                if($method->getName() != 'index') {
                    $query .= '/' . strtolower($method->getName());
                }

                // front::index as root
                if($query == '/front') {
                    $query = '/';
                }

                // args
                $args = $method->getParameters();
                foreach($args as $arg) {

                    // optional param : save last route
                    if($arg->isOptional()) {
                        $routes[$query] = [$class, $method];
                    }

                    // add to query
                    $query .= '/:' . strtolower($arg->getName());
                }

                // save (last) route
                $routes[$query] = [$class, $method];
            }

        }

        return new self($routes);
    }


    /**
     * Setup routes from annotations
     * @param array $classes
     * @throws \InvalidArgumentException
     * @return Router
     */
    public static function annotations(array $classes)
    {
        $routes = [];

        // for all classes
        foreach($classes as $class) {

            // not class
            if(!class_exists($class)) {
                throw new \InvalidArgumentException('Class "' . $class . '" not found.');
            }

            // and all methods
            foreach(get_class_methods($class) as $method) {

                // @route specified
                if($url = Annotation::method($class, $method, 'route')) {
                    $routes[$url] = [$class, $method];
                }

            }

        }

        return new self($routes);
    }


    /**
     * Setup routes from files
     * @param string $dir
     * @param mixed $action
     * @return Router
     */
    public static function files($dir, $action)
    {
        // get all files and sub files
        $files = new GlobFinder($dir, '*');

        // make routes
        $routes = [];
        foreach($files as $file) {

            // clean path
            $path = str_replace($dir, null, pathinfo($file, PATHINFO_BASENAME));
            $path = '/' . ltrim($path, '/');
            $template = str_replace($dir, null, $file);

            // index route
            if($path == '/index') {
                $path = '/';
            }

            // make route
            $route = new Route($path, $action);
            $route->meta['render'] = $template;
            $routes[] = $route;
        }

        return new self($routes);
    }

}