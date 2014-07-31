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

use Craft\Remote\FTP as NativeFTP;

class FTP extends NativeFTP implements Adapter
{


    /**
     * Read file
     * @param string $filename
     * @return $this
     */
    public function read($filename)
    {
        $tmp = tmpfile();
        ftp_fget($this->remote, $tmp, $filename, FTP_BINARY);
        $content = stream_get_contents($tmp, -1, 0);
        fclose($tmp);
        return $content;
    }


    /**
     * Upload file to ftp
     * @param string $filename
     * @param string $content
     * @param int $where
     * @return $this
     */
    public function write($filename, $content, $where = self::REPLACE)
    {
        $tmp = tmpfile();

        if($where = self::BEFORE) {
            $content .= $this->read($filename);
        }
        elseif($where = self::AFTER) {
            $content = $this->read($filename) . $content;
        }

        fwrite($tmp, $content);
        $bool = ftp_fput($this->remote, $filename, $tmp, FTP_BINARY);
        fclose($tmp);
        return $bool;
    }


    /**
     * Delete path
     * @param string $path
     * @return bool
     */
    public function delete($path)
    {
        return $this->drop($path);
    }

}