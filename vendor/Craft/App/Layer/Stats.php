<?php

namespace Craft\App\Layer;

use Craft\App\Layer;
use Craft\App\Request;
use Craft\App\Response;
use Forge\Logger;
use Forge\Session;

/**
 * Keep elapsed time in memory
 */
class Stats extends Layer
{

    /**
     * End of execution
     * @param Request $request
     * @param Response $response
     */
    public function finish(Request $request, Response $response)
    {
        // get data
        $elapsed = microtime(true) - $request->start;
        $average = Session::get('craft.time.average');
        $i = Session::get('craft.time.i');

        // update
        $average = (($average * $i) + $elapsed) / ++$i;
        Session::set('craft.time.average', $average);
        Session::set('craft.time.i', $i);

        Logger::info('App.Statistics : execution time ' . number_format($elapsed, 4) . 's');
        Logger::info('App.Statistics : average execution time ' . number_format($average, 4) . 's (i:' . $i . ')');
    }

}