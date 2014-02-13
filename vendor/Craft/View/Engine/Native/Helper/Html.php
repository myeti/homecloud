<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\View\Engine\Native\Helper;

use Craft\View\Engine\Native\Helper;

class Html extends Helper
{

    /**
     * Basic meta
     * @return string
     */
    public function meta()
    {
        $meta = [
            '<meta charset="UTF-8">',
            '<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, maximum-scale=1" />'
        ];
        return implode("\n", $meta);
    }


    /**
     * Escape string
     * @param $string
     * @return string
     */
    public function e($string)
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

}