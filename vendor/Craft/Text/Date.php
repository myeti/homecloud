<?php

namespace Craft\Text;

abstract class Date
{

    /**
     * Get current date
     * @param string $format
     * @return string
     */
    public static function now($format = 'd/m/Y H:i:s')
    {
        return date($format);
    }

}