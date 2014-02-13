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

use craft\error\FileNotFoundException;

abstract class File
{

	/**
	 * Check if a file exists
	 * @param  string $filename
	 * @return bool
	 */
	public static function exists($filename)
	{
		return file_exists($filename);
	}

	/**
	 * Read file content
	 * @param  string $filename
	 * @return string
	 */
	public static function read($filename)
	{
		return file_get_contents($filename);
	}

	/**
	 * Write content in file
	 * @param  string $filename
	 * @param  string $content
	 * @return bool
	 */
	public static function write($filename, $content)
	{
		return file_put_contents($filename, $content);
	}

    /**
     * Move file
     * @param string $filename
     * @param string $to
     * @return bool
     */
	public static function move($filename, $to)
	{
		// clean to
		if(is_dir($to)) {
			$to .= DIRECTORY_SEPARATOR . basename($filename);
		}

		return rename($filename, $to);
	}

    /**
     * Rename file
     * @param string $filename
     * @param string $to
     * @return bool
     */
	public static function rename($filename, $to)
	{
		return rename($filename, $to);
	}

	/**
	 * Delete file
	 * @param  string $filename
	 * @return bool
	 */
	public static function delete($filename)
	{
		return unlink($filename);
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
		if(!isset($FILES[$name])) {
			throw new \InvalidArgumentException('No uploaded file named "' . $name . '".');
		}

		// resolve path
		if(is_dir($to)) {
			$to .= DIRECTORY_SEPARATOR . $FILES[$name]['name'];
		}

		return move_uploaded_file($FILES[$name]['tmp_name'], $to);
	}

    /**
     * Force download file
     * @param  string $filename
     * @throws FileNotFoundException
     */
	public static function download($filename)
	{
		// error
		if(!file_exists($filename)) {
			throw new FileNotFoundException('File "' . $filename . '" not found.');
		}

		// init & exit
		header('Content-Type: application/octet-stream');
		header('Content-Transfer-Encoding: Binary');
		header('Content-disposition: attachment; filename="' . basename($filename) . '"');
		readfile($filename);
		exit;
	}

}