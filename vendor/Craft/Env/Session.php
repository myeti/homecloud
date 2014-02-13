<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Env;

use Craft\Data\StaticProvider;

abstract class Session extends StaticProvider
{

    /**
     * Create provider instance
     * @return SessionRepository
     */
    protected static function createInstance()
    {
        return new SessionRepository('_craft.session');
    }

} 