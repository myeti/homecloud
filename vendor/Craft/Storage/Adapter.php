<?php

namespace Craft\Storage;

interface Adapter
{

    const BEFORE = 1;
    const REPLACE = 2;
    const AFTER = 3;

    /**
     * List files and dirs
     * @param string $path
     * @return array
     */
    public function listing($path = null);

    /**
     * Check if path exists
     * @param string $path
     * @return bool
     */
    public function has($path);

    /**
     * Read file content
     * @param string $filename
     * @return string
     */
    public function read($filename);

    /**
     * Write content into file, create if not exists
     * @param string $filename
     * @param string $content
     * @param int $where
     * @return bool
     */
    public function write($filename, $content, $where = self::REPLACE);

    /**
     * Create path
     * @param string $path
     * @param int $chmod
     * @return bool
     */
    public function create($path, $chmod = 0755);

    /**
     * Delete path
     * @param string $path
     * @return bool
     */
    public function delete($path);

    /**
     * Rename path
     * @param string $old
     * @param string $new
     * @return bool
     */
    public function rename($old, $new);

} 