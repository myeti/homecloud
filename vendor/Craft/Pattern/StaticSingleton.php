<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Pattern;

use Craft\Error\NotImplementedException;

trait StaticSingleton
{

    /**
     * Get singleton instance
     * @throws NotImplementedException
     * @return mixed;
     */
    protected static function instance()
    {
        static $instance;
        if(!$instance) {

            // not implemented
            if(!method_exists(get_called_class(), 'createInstance')) {
                throw new NotImplementedException('You must implement createInstance() method.');
            }

            $instance = static::createInstance();
        }

        return $instance;
    }

} 