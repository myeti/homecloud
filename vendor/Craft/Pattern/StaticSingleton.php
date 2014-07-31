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

use Craft\Error\NotImplemented;

trait StaticSingleton
{

    /**
     * Get singleton instance
     * @throws NotImplementedException
     * @return mixed;
     */
    protected static function instance($newInstance = null)
    {
        static $instance;
        if($newInstance) {
            $instance = $newInstance;
        }
        if(!$instance) {
            $instance = static::bind();
        }

        return $instance;
    }


    /**
     * Create instance
     * @return null
     */
    protected static function bind()
    {
        throw new NotImplemented('You must override this method.');
    }

} 