<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Kernel;

use Craft\Env\Config;
use Craft\Env\Mog;
use Craft\Pattern\Event\Subject;
use Craft\Reflect\Injector;
use Craft\Router\Matcher;
use Craft\Router\Matcher\UrlMatcher;
use Craft\Router\Route;
use Craft\Router\RouteProvider;
use Craft\View\Engine;
use Craft\View\Engine\Native;
use Craft\View\Engine\Native\Helper\Asset;

class App extends Dispatcher
{

    /** @var Matcher */
    protected $router;

    /** @var Engine */
    protected $engine;


    /**
     * Setup router
     * @param array $routes
     * @param Injector $injector
     * @param Engine $engine
     */
    public function __construct(array $routes, Injector $injector = null, Engine $engine = null)
    {
        // init router
        $this->router = new UrlMatcher(new RouteProvider($routes));

        // init engine
        if(!$engine) {
            $engine = new Native(dirname($_SERVER['SCRIPT_FILENAME']), 'php', []);
            $engine->mount(new Asset(Mog::base()));
        }
        $this->engine = $engine;

        // init dispatcher
        parent::__construct($injector);
    }


    /**
     * Main process
     * @param string $query
     * @return mixed
     */
    public function plug($query = null)
    {
        // start
        $this->fire('app.start', [&$query]);

        // resolve protocol query
        $query = $query ?: $this->query();

        // run router
        $this->fire('app.route', [&$query]);
        $route = $this->route($query);

        // 404
        if(!$route) {
            $this->fire(404, ['message' => 'Route "' . $query . '" not found.']);
            return false;
        }

        // set env data
        foreach($route->data['envs'] as $key => $value) {
            Config::set($key, $value);
        }

        // run dispatcher
        $data = $this->dispatch($route, $this->engine);

        // stop
        $this->fire('app.stop', [&$query, &$route, &$data]);
        return $data;
    }


    /**
     * Get request query
     * @return string
     */
    protected function query()
    {
        $query = Mog::url();
        $query = substr($query, strlen(Mog::base()));
        return parse_url($query, PHP_URL_PATH);
    }


    /**
     * Find route with query
     * @param $query
     * @return Route
     */
    protected function route($query)
    {
        return $this->router->find($query);
    }


    /**
     * Run dispatcher
     * @param Route $route
     * @param Engine $engine
     * @return mixed
     */
    protected function dispatch(Route $route, Engine $engine = null)
    {
        $args = isset($route->data['args']) ? $route->data['args'] : [];
        return $this->perform($route->target, $args, $engine);
    }


    /**
     * App as a service
     * @param string $query
     * @return mixed
     */
    public function __invoke($query = null)
    {
        return $this->plug($query);
    }

}