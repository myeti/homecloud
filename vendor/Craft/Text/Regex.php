<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Text;

use Craft\Data\ArrayCollection;

abstract class Regex
{

    /**
     * Simply regex matching
     * @param $string
     * @param $pattern
     * @param bool $string_keys
     * @internal param null $filter
     * @return array|bool
     */
    public static function match($string, $pattern, $string_keys = false)
    {
        // does it match ?
        if(preg_match($pattern, $string, $matches)) {

            // remove first item
            unset($matches[0]);

            // filter string keys
            if($string_keys) {
                $matches = new ArrayCollection($matches);
                $matches->filterKey(function($key){
                    return !is_int($key);
                });
            }

            // return matched env
            return $matches;
        }

        return false;
    }


    /**
     * Alias of match()
     * @param $string
     * @param $pattern
     * @return array|bool
     */
    public static function extract($string, $pattern)
    {
        return static::match($string, $pattern, true);
    }


    /**
     * Replace substring using regex
     * @param string $string
     * @param string $pattern
     * @param string|array $replacement
     * @return mixed
     */
    public static function replace($string, $pattern, $replacement)
    {
        return ($replacement instanceof \Closure)
            ? preg_replace_callback($pattern, $replacement, $string)
            : preg_replace($pattern, $replacement, $string);
    }


    /**
     * Split string using separator regex
     * @param $string
     * @param $separator
     * @param int $flag
     * @return array
     */
    public static function split($string, $separator, $flag = PREG_SPLIT_NO_EMPTY)
    {
        return preg_split($separator, $string, -1, $flag);
    }


    /**
     * Wrap string
     * @param $string
     * @param $wrapper
     * @return mixed
     */
    public static function wrap($string, $wrapper)
    {
        return static::replace($string, '/^' . $string . '$/', $wrapper);
    }

} 