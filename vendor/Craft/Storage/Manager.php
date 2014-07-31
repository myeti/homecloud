<?php

namespace Craft\Storage;

class Manager implements Adapter
{

    /** @var Adapter */
    protected $adapter;


    /**
     * Setup file manager
     * @param Adapter $adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }


    /**
     * List files and dirs
     * @param string $path
     * @return array
     */
    public function listing($path = null)
    {
        return $this->adapter->listing($path);
    }


    /**
     * Check if path exists
     * @param string $path
     * @return bool
     */
    public function has($path)
    {
        return $this->adapter->has($path);
    }


    /**
     * Read file content
     * @param string $filename
     * @return string
     */
    public function read($filename)
    {
        return $this->adapter->read($filename);
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
        return $this->adapter->write($filename, $content, $where);
    }


    /**
     * Create path
     * @param string $path
     * @param int $chmod
     * @return bool
     */
    public function create($path, $chmod = 0755)
    {
        return $this->adapter->create($path, $chmod);
    }


    /**
     * Delete path
     * @param string $path
     * @return bool
     */
    public function delete($path)
    {
        return $this->adapter->delete($path);
    }


    /**
     * Rename path
     * @param string $old
     * @param string $new
     * @return bool
     */
    public function rename($old, $new)
    {
        return $this->adapter->rename($old, $new);
    }


    /**
     * Alias of rename
     * @param string $old
     * @param string $new
     * @return bool
     */
    public function move($old, $new)
    {
        return $this->adapter->rename($old, $new);
    }

}