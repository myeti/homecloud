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

use Craft\Debug\Tracker;
use Craft\Env\Auth;
use Craft\Pattern\Event\Subject;
use Craft\Reflect\Action;
use Craft\Reflect\Injector;
use Craft\Reflect\Resolver;
use Craft\View\Viewable;
use Craft\View\Engine;

class Dispatcher
{

    use Subject;

    /** @var Injector */
    protected $injector;


    /**
     * Setup injector
     * @param Injector $injector
     */
    public function __construct(Injector $injector = null)
    {
        $this->injector = $injector ?: new Injector();
    }


    /**
     * Run action & render template
     * @param $query
     * @param array $args
     * @param \Craft\View\Engine $engine
     * @return mixed
     */
    public function perform($query, array $args = [], Engine $engine = null)
    {
        // start
        $this->fire('dispatcher.start', [&$query, &$args]);

        // resolve
        $this->fire('dispatcher.resolve', [&$query, &$args]);
        $action = $this->resolve($query, $args);

        // firewall
        $this->fire('dispatcher.firewall', [&$query, &$args, &$action]);
        if(!$this->firewall($action)) {
            $this->fire(403);
            return false;
        }

        // call
        $this->fire('dispatcher.call', [&$query, &$args, &$action]);
        $this->call($action);

        // render
        $this->fire('dispatcher.render', [&$engine, &$action]);
        if($engine) {
            $this->render($engine, $action);
        }

        $this->fire('dispatcher.stop', [&$action]);
        return $action->data;
    }


    /**
     * Resolve and prepare action
     * @param $query
     * @param array $args
     * @return bool|\Craft\Reflect\Action
     * @throws \BadMethodCallException
     */
    protected function resolve($query, array $args = [])
    {
        $action = Resolver::resolve($query, $this->injector);
        if(!$action) {
            throw new \BadMethodCallException('This action is not a valid callable.');
        }

        $action->args = $args;
        $action->metadata += [
            'render' => null,
            'auth'   => 0
        ];

        return $action;
    }


    /**
     * Gate keeper : check auth
     * @param Action $action
     * @return bool
     */
    protected function firewall(Action $action)
    {
        return Auth::allowed($action->metadata['auth']);
    }


    /**
     * Execute action
     * @param Action $action
     * @return mixed
     */
    protected function call(Action $action)
    {
        return call_user_func_array($action, $action->args);
    }


    /**
     * Render view
     * @param \Craft\View\Engine $engine
     * @param Action $action
     */
    protected function render(Engine $engine, Action $action)
    {
        $data = isset($action->data) ? $action->data : [];
        echo $engine->render($action->metadata['render'], $data);
    }

}

