<?php

namespace Forge;

use Craft\Data\Provider\Container;
use Craft\Data\Provider\ProviderObject;

abstract class Bag extends Container
{

    /**
     * Create provider instance
     * @return ProviderObject
     */
    protected static function bind()
    {
        return new ProviderObject;
    }

} 