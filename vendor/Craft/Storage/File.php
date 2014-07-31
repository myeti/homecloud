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

use Craft\Error\FileNotFound;

abstract class File
{

	/**
	 * Check if a file exists
	 * @param  string $path
	 * @return bool
	 */
	public static function has($path)
	{
		return static::local()->has($path);
	}


	/**
	 * Read file content
	 * @param  string $filename
	 * @return string
	 */
	public static function read($filename)
	{
		return static::local()->read($filename);
	}


	/**
	 * Write content in file
	 * @param  string $filename
	 * @param  string $content
	 * @return bool
	 */
	public static function write($filename, $content)
	{
        return static::local()->write($filename, $content);
	}


    /**
     * Rename file
     * @param string $old
     * @param string $new
     * @return bool
     */
    public static function rename($old, $new)
    {
        return static::local()->rename($old, $new);
    }


    /**
     * Move file
     * @param string $old
     * @param string $new
     * @return bool
     */
	public static function move($old, $new)
	{
		return static::local()->rename($old, $new);
	}


	/**
	 * Delete file
	 * @param  string $path
	 * @return bool
	 */
	public static function delete($path)
	{
        return static::local()->delete($path);
	}


    /**
     * Get file extension
     * @param  string $filename
     * @return string
     */
    public static function name($filename)
    {
        return pathinfo($filename, PATHINFO_FILENAME);
    }


	/**
	 * Get file extension
	 * @param  string $filename
	 * @return string
	 */
	public static function ext($filename)
	{
		return pathinfo($filename, PATHINFO_EXTENSION);
	}


	/**
	 * Get file size
	 * @param  string $filename
	 * @return int
	 */
	public static function size($filename)
	{
		return filesize($filename);
	}


    /**
     * Upload file
     * @param  string $name
     * @param  string $to
     * @throws \InvalidArgumentException
     * @return bool
     */
	public static function upload($name, $to)
	{
		// error
		if(!isset($_FILES[$name])) {
			throw new \InvalidArgumentException('No uploaded file named "' . $name . '".');
		}

		// resolve path
		if(is_dir($to)) {
			$to .= DIRECTORY_SEPARATOR . $_FILES[$name]['name'];
		}

		return move_uploaded_file($_FILES[$name]['tmp_name'], $to);
	}


    /**
     * Force download file
     * @param  string $filename
     * @throws FileNotFound
     */
	public static function download($filename)
	{
		// error
		if(!file_exists($filename)) {
			throw new FileNotFound('File "' . $filename . '" not found.');
		}

		// init & exit
		header('Content-Type: application/octet-stream');
		header('Content-Transfer-Encoding: Binary');
		header('Content-disposition: attachment; filename="' . basename($filename) . '"');
		readfile($filename);
		exit;
	}


    /**
     * Get local instance
     * @return Adapter
     */
    protected static function local()
    {
        static $local;
        if(!$local) {
            $local = new Local(null);
        }

        return $local;
    }

}