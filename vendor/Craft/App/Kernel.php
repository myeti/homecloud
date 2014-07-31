<?php

namespace Craft\App;

use Craft\Error\Abort;
use Craft\Event\Subject;
use Forge\Logger;

/**
 * Advanced Dispatcher :
 * manages inner events
 * and plug layers.
 */
class Kernel extends Dispatcher
{

    use Subject;

    /** @var Layer[] */
    protected $layers = [];

    /** @var bool */
    protected $running = false;


    /**
     * Add layer
     * @param Layer $layer
     * @return $this
     */
    public function plug(Layer $layer)
    {
        $class = get_class($layer);
        $this->layers[$class] = $layer;
        Logger::info('App.Kernel : layer "' . $class . '" plugged');

        return $this;
    }


    /**
     * Get inner layer
     * @param string $class
     * @return bool|Layer
     */
    public function layer($class)
    {
        return isset($this->layers[$class])
            ? $this->layers[$class]
            : false;
    }


    /**
     * Handle context request
     * @param Request $request
     * @param Response $response
     * @throws Abort
     * @throws \Exception
     * @return bool
     */
    public function handle(Request $request = null, Response $response = null)
    {
        Logger::info('App.Kernel : kernel ' . ($this->running ? 'restart' : 'start'));
        $this->running = true;

        // resolve request
        if(!$request) {
            $request = Request::generate();
        }

        // safe
        try {

            // execute 'before' layer
            foreach($this->layers as $before) {
                $return = $before->before($request);
                if($return instanceof Request) {
                    $request = $return;
                }
            }

            // dispatch
            $response = parent::handle($request, $response);

            // execute 'after' layer
            foreach($this->layers as $after) {
                $return = $after->after($request, $response);
                if($return instanceof Response) {
                    $response = $return;
                }
            }

            // send response
            echo $response;
            Logger::info('App.Kernel : response sent with code ' . $response->code);

            // finish process
            foreach($this->layers as $finish) {
                $finish->finish($request, $response);
            }

        }
        // abort
        catch(Abort $e) {

            Logger::error('App.Kernel : ' . $e->getCode() . ' ' . $e->getMessage());

            // error as event (if no listener registered, then raise error)
            $done = $this->fire($e->getCode(), [$request, $e->getMessage()]);
            if(!$done) {
                throw $e;
            }

            return false;
        }

        $this->running = false;
        Logger::info('App.Kernel : kernel end');

        return true;
    }


    /**
     * Emulate url request
     * @param $query
     * @return Response
     */
    public function to($query)
    {
        $request = new Request($query);
        return $this->handle($request);
    }


    /**
     * 404 Not found
     * @param string $to
     * @return $this
     */
    public function lost($to)
    {
        $this->on(404, function() use($to) {
            Logger::info('App.Kernel : error 404, redirect to "' . $to . '"');
            $this->to($to);
        });

        return $this;
    }


    /**
     * 403 Forbidden
     * @param string $to
     * @return $this
     */
    public function nope($to)
    {
        $this->on(403, function() use($to) {
            Logger::info('App.Kernel : error 403, redirect to "' . $to . '"');
            $this->to($to);
        });

        return $this;
    }

}