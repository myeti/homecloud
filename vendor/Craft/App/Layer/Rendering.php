<?php

namespace Craft\App\Layer;

use Craft\App\Layer;
use Craft\App\Request;
use Craft\App\Response;
use Craft\Box\Mog;
use Forge\Logger;
use Craft\View\Engine;
use Craft\View\EngineInterface;
use Craft\View\Helper\Markup;

/**
 * Render view using the html engine
 * when @render in specified.
 *
 * Needs Layer\Metadata
 */
class Rendering extends Layer
{

    /** @var Engine */
    protected $engine;


    /**
     * Setup engine
     * @param string $root
     */
    public function __construct($root = null)
    {
        if($root instanceof EngineInterface) {
            $this->engine = $root;
        }
        else {
            $this->engine = new Engine($root ?: Mog::path());
            $this->engine->mount(new Markup(Mog::base()));
        }
    }


    /**
     * Handle response
     * @param Response $response
     * @param Request $request
     * @return Response
     */
    public function after(Request $request, Response $response = null)
    {
        // render if metadata provided
        if(!empty($request->meta['render'])) {
            $response->content = $this->engine->render($request->meta['render'], $response->data);
            Logger::info('App.Html : render template "' . $request->meta['render'] . '" as html');
        }

        return $response;
    }

}