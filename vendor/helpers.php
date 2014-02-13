<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */


/**
 * Get absolute path
 * @return string
 */
function path()
{
    return call_user_func_array('\Craft\Env\Mog::path', func_get_args());
}


/**
 * Get complete url
 * "Ce truc, ça fait les frites !" - Rudy
 * @return string
 */
function url()
{
    $segments = func_get_args();
    return rtrim(Craft\Env\Mog::base(), '/') . '/' . ltrim(implode('/', $segments), '/');
}


/**
 * Redirect to url
 */
function go()
{
    $segments = func_get_args();
    header('Location: ' . call_user_func_array('url', $segments));
    exit;
}


/**
 * Debug var
 */
function debug()
{
    die(call_user_func_array('var_dump', func_get_args()));
}


/**
 * Post helper
 * @param null $key
 * @param string $fallback
 * @return null
 */
function post($key = null, $fallback = null)
{
    return Craft\Env\Mog::post($key, $fallback);
}


/**
 * Read-only Auth
 * @return \stdClass
 */
function auth()
{
    return (object)[
        'logged'    => Craft\Env\Auth::logged(),
        'rank'      => Craft\Env\Auth::rank(),
        'user'      => Craft\Env\Auth::user()
    ];
}


/**
 * Read-only Flash
 * @param string $key
 * @return string
 */
function flash($key)
{
    return Craft\Env\Flash::get($key);
}


/**
 * Alias of craft\I18n::translate()
 * @param  string $text
 * @param  array $vars
 * @return string
 */
function __($text, array $vars = [])
{
    return Craft\Text\I18n::translate($text, $vars);
}


/**
 * Hydrate object with env
 * @param $object
 * @param array $data
 * @return object
 */
function hydrate($object, array $data)
{
    return Craft\Reflect\Object::hydrate($object, $data);
}


/**
 * Return object instance for chaining
 * @param $object
 * @return object
 */
function with($object)
{
    return Craft\Reflect\Object::with($object);
}


/**
 * Get first element of array
 * @param $array
 * @return mixed
 */
function array_first(array $array)
{
    $collection = new Craft\Data\ArrayCollection($array);
    return $collection->first($array);
}


/**
 * Get last element of array
 * @param $array
 * @return mixed
 */
function array_last(array $array)
{
    $collection = new Craft\Data\ArrayCollection($array);
    return $collection->last($array);
}


/**
 * Get value or null, don't throw error
 * @param $array
 * @param $key
 * @return null
 */
function array_get_silent($array, $key)
{
    $collection = new Craft\Data\ArrayCollection($array);
    return $collection->get($key);
}


/**
 * Sort array by column names and directions
 * Ex : $sorted = array_sort($array, ['name' => SORT_DESC]);
 * @param array $array
 * @param array $set
 * @return mixed
 */
function array_sort(array $array, array $set)
{
    $collection = new Craft\Data\ArrayCollection($array);
    return $collection->sort($set);
}


/**
 * Alias of Strong::compose()
 * @param $string
 * @param array $vars
 * @return mixed
 */
function compose($string, array $vars = [])
{
    return Craft\Text\String::compose($string, $vars);
}


/**
 * Return input reference
 * @param $something
 * return mixed
 */
function ref(&$something)
{
    return $something;
}