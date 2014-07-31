<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Storage\Finder;

class RegexFinder extends \RegexIterator
{

    /**
     * Recursive regex file finder
     * @param string $in
     * @param string $regex
     * @param int $flags
     */
    public function __construct($in, $regex, $flags = \FilesystemIterator::SKIP_DOTS)
    {
        $directory = new \RecursiveDirectoryIterator($in, $flags);
        $iterator = new \RecursiveIteratorIterator($directory);
        parent::__construct($iterator, $regex);
    }


    /**
     * Check if file exists
     * @param $in
     * @param $regex
     * @return bool
     */
    public static function has($in, $regex)
    {
        $iterator = new self($in, $regex);
        return $iterator->valid();
    }

}