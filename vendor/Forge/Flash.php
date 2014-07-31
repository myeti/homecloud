<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Forge;

use Craft\Box\Flash as FlashProvider;
use Craft\Data\Provider;
use Craft\Data\Provider\Container;

abstract class Flash extends Container
{

    /**
     * Create provider instance
     * @return FlashProvider
     */
    protected static function bind()
    {
        return new FlashProvider;
    }

}