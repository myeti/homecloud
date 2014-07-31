<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */


/**
 * Flash helper
 * @param string $key
 * @param string $fallback
 * @return string
 */
function flash($key, $fallback = null)
{
    return Forge\Flash::get($key, $fallback);
}