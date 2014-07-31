<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Text;

abstract class Lang
{

	/** @var array */
	protected static $table = [];


	/**
	 * Index table
	 * @param array $table
	 */
	public static function index(array $table)
	{
		// create hash
		foreach($table as $key => $value) {
			static::$table[md5($key)] = $value;
		}
	}


	/**
	 * Load indexed table
	 * @param array $table
	 */
	public static function load(array $table)
	{
        static::$table = $table;
	}


	/**
	 * Translate message
	 * @param  string $text
	 * @param  array $vars
	 * @return string
	 */
	public static function translate($text, array $vars = [])
	{
		// clean
		$text = trim($text);

		// get table text
        $md5 = md5($text);
		if(isset(static::$table[$md5])) {
			$text = static::$table[$md5];
		}

		// compile cloze
		$text = String::compose($text, $vars);

		return $text;
	}

}