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

class GlobFinder extends \AppendIterator
{

    /**
     * Setup iterator
     * @param string $path
     * @param string $glob
     * @param int $flags
     */
    public function __construct($path, $glob, $flags = \FilesystemIterator::SKIP_DOTS)
    {
        // init parent
        parent::__construct();

        // parse
        $glob = ltrim($glob, '/');
        $path = rtrim($path, '/') . '/';

        // glob finder
        $this->append(new \GlobIterator($path . $glob, $flags));

        // go deeper
        $sub = glob($path . '*', GLOB_ONLYDIR);
        foreach($sub as $dir) {
            $this->append(new self($dir, $glob, $flags));
        }
    }


    /**
     * Check if file exists
     * @param string $path
     * @param string $glob
     * @return bool
     */
    public static function has($path, $glob)
    {
        $iterator = new self($path, $glob);
        return $iterator->valid();
    }

}