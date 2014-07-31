<?php

namespace Craft\App;

/**
 * A layer is a middleware
 * that operates before and/or after
 * the action is called
 */
abstract class Layer
{

    const ROUTER = 'router';
    const AUTH = 'auth';
    const VIEW = 'view';

    /**
     * Handle request
     * @param Request $request
     * @return Request
     */
    public function before(Request $request)
    {
        return $request;
    }

    /**
     * Handle response
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function after(Request $request, Response $response)
    {
        return $response;
    }

    /**
     * End of execution
     * @param Request $request
     * @param Response $response
     */
    public function finish(Request $request, Response $response) {}

} 