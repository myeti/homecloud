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

class In
{

    /** @var resource */
    protected $scanner;


    /**
     * Setup input scanner
     * @param resource $resource
     */
    public function __construct($resource = null)
    {
        $this->scanner = $resource ?: fopen('php://stdin', 'r');
    }


    /**
     * Get user input
     * @return string
     */
    public function read()
    {
        $input = fgets($this->scanner);
        return trim($input);
    }

    /**
     * Ask question and get user answer
     * @param $message
     * @return string
     */
    public function ask($message)
    {
        echo $message;
        return $this->read();
    }

}