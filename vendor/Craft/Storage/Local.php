<?php

namespace Craft\Storage;

class Local implements Adapter
{

    /** @var string */
    protected $root;

    /** @var array */
    protected $cache = [];


    /**
     * Set root path
     * @param string $root
     */
    public function __construct($root)
    {
        $root = str_replace('/', DIRECTORY_SEPARATOR, $root);
        $root = str_replace('\\', DIRECTORY_SEPARATOR, $root);
        $root = rtrim($root, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->root = $root;
    }


    /**
     * List files and dirs
     * @param string $path
     * @param int $flags
     * @return array
     */
    public function listing($path = null, $flags = null)
    {
        $path = $this->make($path);
        $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '*';
        return glob($path, $flags);
    }


    /**
     * Check if path exists
     * @param string $path
     * @return bool
     */
    public function has($path)
    {
        $path = $this->make($path);
        return file_exists($path);
    }


    /**
     * Read file content
     * @param string $filename
     * @return string
     */
    public function read($filename)
    {
        $path = $this->make($filename);
        return file_get_contents($path);
    }


    /**
     * Write content into file, create if not exists
     * @param string $filename
     * @param string $content
     * @param int $where
     * @return bool
     */
    public function write($filename, $content, $where = self::REPLACE)
    {
        $path = $this->make($filename);
        $base = dirname($path);

        // create dir
        if(!file_exists($base)) {
            mkdir($base, 0755, true);
        }

        // write
        if($where == self::BEFORE) {
            $content = $content . file_get_contents($path);
        }
        elseif($where == self::AFTER) {
            $content = file_get_contents($path) . $content;
        }

        return file_put_contents($path, $content);
    }


    /**
     * Create path
     * @param string $path
     * @param int $chmod
     * @return bool
     */
    public function create($path, $chmod = 0755)
    {
        $path = $this->make($path);
        return mkdir($path, $chmod, true);
    }


    /**
     * Delete path
     * @param string $path
     * @return bool
     */
    public function delete($path)
    {
        $path = $this->make($path);
        return is_dir($path) ? rmdir($path) : unlink($path);
    }


    /**
     * Rename path
     * @param string $old
     * @param string $new
     * @return bool
     */
    public function rename($old, $new)
    {
        $old = $this->make($old);
        $new = $this->make($new);
        return rename($old, $new);
    }


    /**
     * Make full path
     * @param string $path
     * @return string
     */
    protected function make($path)
    {
        // already made
        if(isset($this->cache[$path])) {
            return $this->cache[$path];
        }

        // clean path
        $full = str_replace('/', DIRECTORY_SEPARATOR, $path);
        $full = str_replace('\\', DIRECTORY_SEPARATOR, $full);
        $full = $this->root . ltrim($full, DIRECTORY_SEPARATOR);

        // add in cache
        $this->cache[$path] = $full;

        return $full;
    }

}