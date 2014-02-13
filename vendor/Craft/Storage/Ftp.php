<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Storage;

class Ftp
{

    /** @var resource */
    protected $remote;


    /**
     * Open a FTP remote connection
     * @param $url
     * @param bool $secure
     */
    public function __construct($url, $secure = false)
    {
        $this->remote = $secure ? ftp_ssl_connect($url) : ftp_connect($url);
    }


    /**
     * Log in ftp
     * @param $username
     * @param $password
     * @return bool
     */
    public function login($username, $password)
    {
        return ftp_login($this->remote, $username, $password);
    }


    /**
     * Get current directory
     * @return string
     */
    public function current()
    {
        return ftp_pwd($this->remote);
    }


    /**
     * Get current directory
     * @param string $of
     * @return string
     */
    public function listing($of = '.')
    {
        return ftp_nlist($this->remote, $of);
    }


    /**
     * Move to another directory
     * @param $to
     * @return bool
     */
    public function walk($to)
    {
        $moved = true;
        $dirs = explode('/', trim($to, '/'));
        foreach($dirs as $dir) {
            $moved &= ftp_chdir($this->remote, $dir);
        }

        return $moved;
    }


    /**
     * Delete file or directory
     * @param $file
     * @return bool
     */
    public function delete($file)
    {
        return ftp_delete($this->remote, $file) ?: ftp_rmdir($this->remote, $file);
    }


    /**
     * Create directory
     * @param $directory
     * @param int $mode
     * @internal param int $mod
     * @return bool
     */
    public function create($directory, $mode = null)
    {
        $created = ftp_mkdir($this->remote, $directory);
        if($mode) {
            ftp_chmod($this->remote, $mode, $directory);
        }

        return $created;
    }


    /**
     * Rename file or directory
     * @param $old
     * @param $new
     * @return bool
     */
    public function rename($old, $new)
    {
        return ftp_rename($this->remote, $old, $new);
    }


    /**
     * Move file or directory
     * @param $what
     * @param $to
     * @return bool
     */
    public function move($what, $to)
    {
        $filename = pathinfo($what, PATHINFO_BASENAME);
        $to .= '/' . $filename;
        return ftp_rename($this->remote, $filename, $to);
    }


    /**
     * Download file
     * @param $filename
     * @param $to
     * @return $this
     */
    public function download($filename, $to)
    {
        return ftp_get($this->remote, $to, $filename, FTP_BINARY);
    }


    /**
     * Upload file to ftp
     * @param $filename
     * @param null $to
     * @return $this
     */
    public function upload($filename, $to = null)
    {
        return ftp_put($this->remote, $to, $filename, FTP_BINARY);
    }


    /**
     * Close connection
     */
    public function __destruct()
    {
        ftp_close($this->remote);
    }

} 