<?php

namespace Craft\App\Layer;

use Craft\Error\Forbidden;
use Craft\App\Layer;
use Craft\App\Request;
use Forge\Logger;
use Forge\Auth;

/**
 * Check if user is allowed to execute
 * the requested action when @auth is specified.
 *
 * Needs Layer\Metadata
 */
class Firewall extends Layer
{

    /**
     * Handle request
     * @param Request $request
     * @throws \Craft\Error\Forbidden
     * @return Request
     */
    public function before(Request $request)
    {
        if(!isset($request->meta['auth'])) {
            $request->meta['auth'] = 0;
        }

        if(!Auth::allowed($request->meta['auth'])) {
            throw new Forbidden('User not allowed for query "' . $request->query . '"');
        }

        Logger::info('App.Firewall : user is allowed');

        return $request;
    }

}