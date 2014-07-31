<?php

namespace Craft\Remote;

class FTP
{

    /** @var resource */
    protected $remote;


    /**
     * Open connection
     * @param string $url
     * @param string $username
     * @param string $password
     * @param bool $secure
     */
    public function __construct($url, $username, $password, $secure = false)
    {
        $this->remote = $secure ? ftp_ssl_connect($url) : ftp_connect($url);
        ftp_login($this->remote, $username, $password);
    }


    /**
     * List elements in path
     * @param string $path
     * @return array
     */
    public function listing($path = '.')
    {
        return ftp_nlist($this->remote, $path);
    }


    /**
     * Check if file exists
     * @param string $filename
     * @return bool
     */
    public function has($filename)
    {
        $list = ftp_nlist($this->remote, dirname($filename));
        return in_array($filename, $list);
    }


    /**
     * Download filename
     * @param string $filename
     * @param string $local
     * @return bool
     */
    public function get($filename, $local)
    {
        return ftp_get($this->remote, $local, $filename, FTP_BINARY);
    }


    /**
     * Upload filename
     * @param string $filename
     * @param string $local
     * @return bool
     */
    public function set($filename, $local)
    {
        return ftp_put($this->remote, $filename, $local, FTP_BINARY);
    }


    /**
     * Drop filename
     * @param string $path
     * @return bool
     */
    public function drop($path)
    {
        return ftp_delete($this->remote, $path) ?: ftp_rmdir($this->remote, $path);
    }


    /**
     * Create directory
     * @param string $path
     * @param int $mode
     * @return bool
     */
    public function create($path, $mode = null)
    {
        $bool = ftp_mkdir($this->remote, $path);
        if($mode) {
            ftp_chmod($this->remote, $mode, $path);
        }

        return $bool;
    }


    /**
     * Rename file or directory
     * @param string $old
     * @param string $new
     * @return bool
     */
    public function rename($old, $new)
    {
        return ftp_rename($this->remote, $old, $new);
    }


    /**
     * Close connection
     */
    public function __destruct()
    {
        ftp_close($this->remote);
    }


}