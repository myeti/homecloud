<?php

namespace Forge\App;

use Craft\App\Kernel;
use Craft\App\Layer;
use Craft\App\Layer\Firewall;
use Craft\App\Layer\Json;
use Craft\App\Layer\Metadata;
use Craft\App\Layer\Routing;
use Craft\App\Layer\Stats;
use Craft\Map\Router;

/**
 * Ready to use app
 */
class Service extends Kernel
{

    /**
     * Init app with classes
     * @param array $classes
     */
    public function __construct(array $classes)
    {
        $this->plug(
            new Routing(Router::annotations($classes))
        );

        $this->plug(new Metadata);
        $this->plug(new Firewall);
        $this->plug(new Json);
        $this->plug(new Stats);
    }

}