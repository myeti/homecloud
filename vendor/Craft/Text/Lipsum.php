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

use Craft\Text\Lipsum\Source;

/**
 * Generate fake text based on lorem, cake or pokemon ipsum.
 *
 * Usage :
 * - change source : Lipsum::source(new Source())
 * - make word : Lipsum::word()
 * - make line : Lipsum::line([number_of_words])
 * - make text : Lipsum::text([number_of_words], [number_of_lines])
 * - make many texts : Lipsum::texts([number_of_words], [number_of_lines], [number_of_blocks])
 * - make title : Lipsum::title()
 * - make url : Lipsum::url()
 * - make username : Lipsum::username()
 * - make password : Lipsum::password()
 * - make email : Lipsum::email()
 * - make poetry : Lipsum::poetry()
 * - make date : Lipsum::date()
 * - make number : Lipsum::number()
 */
abstract class Lipsum
{

    /** @var array */
    protected static $exts = ['.com', '.fr', '.net', '.org', '.info'];


    /**
     * Get or set source
     * @param Source $newsource
     * @return Source
     */
    public static function source(Source $newsource = null)
    {
        // set source
        static $source;
        if($newsource) {
            $source = $newsource;
        }
        elseif(!$source) {
            $source = new Lipsum\Source\Cake();
        }

        // get source array
        return $source;
    }


    /**
     * Generate Lipsum text
     * @param null $words
     * @param null $lines
     * @param null $texts
     * @return string
     */
    public static function generate($words = null, $lines = null, $texts = null)
    {
        // default values
        $texts = $texts ?: rand(1, 5);
        $lines = $lines ?: rand(2, 10);
        $words = $words ?: rand(6, 12);

        // generate output
        return static::source()->generate($words, $lines, $texts);
    }


    /**
     * Generate one word
     * @return string
     */
    public static function word()
    {
        return static::generate(1, 1, 1);
    }


    /**
     * Generate one line
     * @param null $words
     * @return string
     */
    public static function line($words = null)
    {
        return static::generate($words, 1, 1);
    }


    /**
     * Generate one text
     * @param null $words
     * @param null $lines
     * @return string
     */
    public static function text($words = null, $lines = null)
    {
        return static::generate($words, $lines, 1);
    }

    /**
     * Generate many text
     * @param null $words
     * @param null $lines
     * @param null $texts
     * @return string
     */
    public static function texts($words = null, $lines = null, $texts = null)
    {
        return static::generate($words, $lines, $texts);
    }


    /**
     * Generate a line of 3..6 words without ending point
     * @return string
     */
    public static function title()
    {
        return rtrim(static::word(rand(3, 6)), '. ');
    }


    /**
     * Generate a random email address
     * @return string
     */
    public static function email()
    {
        $ext = array_rand(array_flip(static::$exts));
        $email = static::word() . '@' . static::word() . $ext;
        return strtolower($email);
    }


    /**
     * Generate a random url
     * @return string
     */
    public static function url()
    {
        $ext = array_rand(array_flip(static::$exts));
        $url = 'http://www.' . static::word() . $ext;
        return strtolower($url);
    }


    /**
     * Generate two random word
     * @return string
     */
    public static function username()
    {
        return rtrim(ucwords(static::line(2)), '. ');
    }


    /**
     * Generate two random word
     * @return string
     */
    public static function password()
    {
        return sha1(static::line());
    }


    /**
     * Generate a poetry formatted text
     * @return string
     */
    public static function poetry()
    {
        return static::texts(4, 5, 6);
    }


    /**
     * Generate random date
     * @return string
     */
    public static function date()
    {
        $min = 381121281;
        $max = time();
        $time = rand($min, $max);
        return date('Y-m-d H:i:s', $time);
    }


    /**
     * Generate random int
     * @return int
     */
    public static function number()
    {
        return rand(0, 20000);
    }

}