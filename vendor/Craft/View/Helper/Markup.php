<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\View\Helper;

use Craft\View\Helper;

class Markup extends Helper
{

    /** @var string */
    protected $base;


    /**
     * Setup base url
     * @param string $base
     */
    public function __construct($base = '/')
    {
        $this->base = rtrim($base, '/') . '/';
    }


    /**
     * Get full file path
     * @param $filename
     * @return string
     */
    public function asset($filename)
    {
        return $this->base . ltrim($filename, '/');
    }


    /**
     * Css tag
     * @param $filename
     * @return string
     */
    public function css($filename)
    {
        $css = [];
        foreach(func_get_args() as $file) {
            $file = ltrim($file, '/') . '.css';
            $css[] = '<link type="text/css" href="' . $this->asset($file) . '" rel="stylesheet" />';
        }
        return implode("\n", $css);
    }


    /**
     * Js tag
     * @param $filename
     * @return string
     */
    public function js($filename)
    {
        $js = [];
        foreach(func_get_args() as $file) {
            $file = ltrim($file, '/') . '.js';
            $js[] = '<script type="text/javascript" src="' . $this->asset($file) . '"></script>';
        }
        return implode("\n", $js);
    }


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