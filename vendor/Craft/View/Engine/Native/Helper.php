<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\View\Engine\Native;

abstract class Helper
{

    /**
     * Register helper functions
     * @return array
     */
    public function register()
    {
        $fns = [];
        foreach(get_class_methods($this) as $method) {
            $fns[$method] = [$this, $method];
        }

        return $fns;
    }

} 