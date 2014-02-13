<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Data;

class SilentArray extends \ArrayObject
{

    /**
     * Silent get : does not throw error
     * @param mixed $index
     * @return mixed
     */
    public function offsetGet($index)
    {
        return isset($this[$index]) ? parent::offsetGet($index) : null;
    }

} 