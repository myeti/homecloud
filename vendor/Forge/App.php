<?php

namespace Forge;

use Craft\App\Kernel;
use Craft\App\Layer;
use Craft\App\Layer\Firewall;
use Craft\App\Layer\Rendering;
use Craft\App\Layer\Metadata;
use Craft\App\Layer\Routing;
use Craft\App\Layer\Stats;
use Craft\Map\RouterInterface;

/**
 * Ready to use app
 */
class App extends Kernel
{

    /**
     * Init app with routes and views dir
     * @param array|RouterInterface $routes
     * @param string $views
     */
    public function __construct($routes = [], $views = null)
    {
        $this->plug(new Routing($routes));
        $this->plug(new Metadata);
        $this->plug(new Firewall);
        $this->plug(new Rendering($views));
        $this->plug(new Stats);
    }

}