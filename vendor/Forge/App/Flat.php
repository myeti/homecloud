<?php

namespace Forge\App;

use Craft\App\Kernel;
use Craft\App\Layer;
use Craft\App\Layer\Rendering;
use Craft\App\Layer\Metadata;
use Craft\App\Layer\Routing;
use Craft\App\Layer\Stats;
use Craft\Map\Router;

/**
 * Ready to use app
 */
class Flat extends Kernel
{

    /**
     * Init app with routes and views dir
     * @param string $views
     */
    public function __construct($views = null)
    {
        $this->plug(
            new Routing(Router::files($views))
        );

        $this->plug(new Metadata);
        $this->plug(new Rendering($views));
        $this->plug(new Stats);
    }

}