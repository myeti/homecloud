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

abstract class I18n
{

	/** @var array */
	protected static $data = [];


	/**
	 * Load translation table
	 * @param  array  $data
	 */
	public static function load(array $data)
	{
		// create hash
		foreach($data as $key => $value) {
			static::$data[md5($key)] = $value;
		}
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

		// get table
		if(isset(static::$data[md5($text)])) {
			$text = static::$data[md5($text)];
		}

		// compile cloze
		$text = String::compose($text, $vars);

		return $text;
	}

}