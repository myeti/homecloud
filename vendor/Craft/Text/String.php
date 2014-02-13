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

abstract class String
{

    /** Position in string */
    const LEFT = 0x1;
    const RIGHT = 0x2;
    const BOUNDS = 0x3;
    const ANYWHERE = 0x4;


    /**
     * Truncate a string
     * @param $string
     * @param $length
     * @param int $where
     * @return string
     */
    public static function cut($string, $length, $where = String::RIGHT)
    {
        if($where & String::LEFT) {
            $string = substr($string, $length);
        }

        if($where & String::RIGHT) {
            $string = substr($string, 0, $length);
        }

        return $string;
    }


    /**
     * Cut and add ellipsis to string
     * @param $string
     * @param $length
     * @return string
     */
    public static function ellipsis($string, $length)
    {
        // big enough
        if($length - 3 > 0) {
            $length -= 3;
        }

        return static::cut($string, $length) . '...';
    }


    /**
     * Divide text depending on length
     * @param $string
     * @param $length
     * @param string $break
     * @return string
     */
    public static function split($string, $length, $break = '<br/>')
    {
        return wordwrap($string, $length, $break, true);
    }


    /**
     * Replace substring
     * @param $string
     * @param $search
     * @param $replacement
     * @return string
     */
    public static function replace($string, $search, $replacement)
    {
        return str_replace($search, $replacement, $string);
    }


    /**
     * Check if segment exists in string
     * @param $string
     * @param $segment
     * @param int $where
     * @return bool
     */
    public static function has($string, $segment, $where = String::ANYWHERE)
    {
        $has = true;

        if($where & String::ANYWHERE) {
            $has &= (strpos($string, $segment) === false);
        }
        else {

            if($where & String::LEFT) {
                $has &= (substr($string, 0, strlen($segment)) == $segment);
            }

            if($where & String::RIGHT) {
                $has &= (substr($string, -strlen($segment)) == $segment);
            }

        }

        return $has;
    }


    /**
     * Remove segment in string
     * @param $string
     * @param $segment
     * @param int $where
     * @return bool
     */
    public static function chop($string, $segment, $where = String::ANYWHERE)
    {
        if($where & String::ANYWHERE) {
            $string = str_replace($segment, '', $string);
        }
        else {

            $length = strlen($segment);

            if($where & String::LEFT and substr($string, 0, $length) == $segment) {
                $string = substr($string, strlen($segment));
            }

            if($where & String::RIGHT and substr($string, $length) == $segment) {
                $string = substr($string, 0, -$length);
            }

        }

        return $string;
    }


    /**
     * Create hash from string
     * @param $string
     * @param string $salt
     * @return string
     */
    public static function hash($string, $salt = '')
    {
        return sha1(sha1($salt) . $string);
    }


    /**
     * Remove and/or add segment on left
     * @param $string
     * @param $segment
     * @param int $where
     * @return string
     */
    public static function ensure($string, $segment, $where = String::RIGHT)
    {
        $string = static::chop($string, $segment, $where);

        if($where & String::LEFT) {
            $string = $segment . $string;
        }

        if($where & String::RIGHT) {
            $string .= $segment;
        }

        return $string;
    }


    /**
     * Get string length
     * @param $string
     * @return int
     */
    public static function size($string)
    {
        return strlen($string);
    }


    /**
     * Generate string from placeholder env
     * @param $string
     * @param array $vars
     * @return mixed
     */
    public static function compose($string, array $vars = [])
    {
        foreach($vars as $placeholder => $value) {
            $string = str_replace(':' . $placeholder, $value, $string);
        }

        return $string;
    }


    /**
     * Extract env from placeholder in mask
     * @param $string
     * @param $mask
     * @return array|bool
     */
    public static function mask($string, $mask)
    {
        // create pattern
        $pattern = '/^' . preg_replace('/:([\w]+)/', '(?<$1>.+)', $mask) . '$/';
        return Regex::extract($string, $pattern);
    }


    /**
     * Strip accents in string
     * @param $string
     * @param string $encoding
     * @return mixed
     */
    public static function stripAccents($string, $encoding = 'utf-8')
    {
        $accents = [
            'àáâãäå'    => 'a',
            'ç'         => 'c',
            'ð'         => 'd',
            'èéêë'      => 'e',
            'ìíîï'      => 'i',
            'ñ'         => 'n',
            'òóôõöø'    => 'o',
            'š'         => 's',
            'ùúûü'      => 'u',
            'ýÿ'        => 'y',
            'ž'         => 'z',
            'æ'         => 'ae',
            'œ'         => 'oe'
        ];

        foreach($accents as $pattern => $replacement) {
            $string = Regex::replace($string, '/[' . $pattern . ']/', $replacement);
        }

        return $string;
    }


    /**
     * Escape string
     * @param $string
     * @return string
     */
    public static function escape($string)
    {
        return htmlspecialchars($string);
    }


    /**
     * Alias of escape
     * @param $string
     * @return string
     */
    public static function e($string)
    {
        return static::escape($string);
    }

}
