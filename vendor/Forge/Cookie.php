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

use Craft\Data\Provider;
use Craft\Data\Provider\Container;
use Craft\Data\Provider\Container\Swap;
use Craft\Box\Cookie as CookieProvider;

abstract class Cookie extends Container
{

    use Swap;

    /**
     * Create provider instance
     * @return CookieProvider
     */
    protected static function bind()
    {
        return new CookieProvider;
    }

} 