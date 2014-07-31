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
    return call_user_func_array('\Craft\Box\Mog::path', func_get_args());
}


/**
 * Get complete url
 * "Ce truc, ça fait les frites !" - Rudy
 * @return string
 */
function url()
{
    $segments = func_get_args();
    return rtrim(Craft\Box\Mog::base(), '/') . '/' . ltrim(implode('/', $segments), '/');
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
    return Craft\Box\Mog::post($key, $fallback);
}


/**
 * Env helper
 * @param null $key
 * @param string $fallback
 * @return null
 */
function env($key = null, $fallback = null)
{
    return Craft\Box\Mog::env($key, $fallback);
}


/**
 * Alias of Lang::translate()
 * @param  string $text
 * @param  array $vars
 * @return string
 */
function __($text, array $vars = [])
{
    return Craft\Text\Lang::translate($text, $vars);
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
 * Alias of String::compose()
 * @param $string
 * @param array $vars
 * @return mixed
 */
function compose($string, array $vars = [])
{
    return Craft\Text\String::compose($string, $vars);
}