<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Cli;

abstract class Dialog
{

    /**
     * Write message
     * @param $message
     * @return $this
     */
    public static function say($message)
    {
        echo implode(' ', func_get_args());
    }


    /**
     * Get user input
     * @return string
     */
    public static function read()
    {
        static $scanner;
        if(!$scanner) {
            $scanner = fopen('php://stdin', 'r');
        }

        $input = fgets($scanner);
        return trim($input);
    }

    /**
     * Ask question and get user answer
     * @param $message
     * @return string
     */
    public static function ask($message)
    {
        static::say($message);
        return static::read();
    }

}