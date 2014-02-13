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

class Out
{

    /**
     * Write message and skip line
     * @param $message
     * @return $this
     */
    public function say($message)
    {
        $this->write($message);
        return $this;
    }


    /**
     * New line
     * @return $this
     */
    public function nl()
    {
        $this->write("\n");
        return $this;
    }


    /**
     * Go back on same line
     * @return $this
     */
    public function back()
    {
        $this->write("\r");
        return $this;
    }


    /**
     * Write message
     * @param $message
     */
    protected function write($message)
    {
        // many lines
        if(is_array($message)) {
            foreach($message as $line) {
                $this->write($line);
            }
        }
        else {
            echo $message;
        }
    }

}