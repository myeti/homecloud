<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Env;

abstract class Mog
{

    /**
     * Get root dir
     * @return string
     */
    public static function path()
    {
        $segments = func_get_args();
        $root = dirname(static::server('SCRIPT_FILENAME'));
        return $root . implode(DIRECTORY_SEPARATOR, $segments);
    }

    /**
     * Return http code
     * @return int
     */
    public static function code()
    {
        return http_response_code();
    }

    /**
     * Is secure
     * @return bool
     */
    public static function https()
    {
        return (static::server('HTTP', 'off') == 'on');
    }


    /**
     * Is not secure
     * @return bool
     */
    public static function http()
    {
        return !static::https();
    }


    /**
     * Get current host
     * @return mixed
     */
    public static function host()
    {
        return static::server('HTTP_HOST');
    }


    /**
     * Get request uri (without host)
     * @return string
     */
    public static function url()
    {
        return static::server('REQUEST_URI');
    }


    /**
     * Get base url (before script file)
     * @return string
     */
    public static function base()
    {
        return dirname(static::server('SCRIPT_NAME')) . '/';
    }


    /**
     * Get full url (host + url)
     * @return string
     */
    public static function fullurl()
    {
        $protocol = static::https() ? 'https' : 'http';
        return $protocol . '://' . static::host() . static::base() . static::url();
    }


    /**
     * $GET value
     * @param  string $key
     * @param  string $fallback
     * @return mixed
     */
    public static function get($key = null, $fallback = null)
    {
        // fallback
        if($key and !isset($_GET[$key])) {
            return $fallback;
        }

        return $key ? $_GET[$key] : $_GET;
    }


    /**
     * $POST value
     * @param  string $key
     * @param  string $fallback
     * @return mixed
     */
    public static function post($key = null, $fallback = null)
    {
        // fallback
        if($key and !isset($_POST[$key])) {
            return $fallback;
        }

        return $key ? $_POST[$key] : $_POST;
    }


    /**
     * $FILES value
     * @param  string $key
     * @param  string $fallback
     * @return array|object
     */
    public static function file($key = null, $fallback = null)
    {
        // fallback
        if($key and !isset($_FILES[$key])) {
            return $fallback;
        }

        return $key ? (object)$_FILES[$key] : $_FILES;
    }


    /**
     * $SERVER value
     * @param  string $key
     * @param  string $fallback
     * @return mixed
     */
    public static function server($key = null, $fallback = null)
    {
        // fallback
        if($key and !isset($_SERVER[strtoupper($key)])) {
            return $fallback;
        }

        return $key ? $_SERVER[strtoupper($key)] : $_SERVER;
    }


    /**
     * headers value
     * @param string $key
     * @param null $fallback
     * @return mixed
     */
    public static function header($key = null, $fallback = null)
    {
        // define headers
        $headers = function_exists('getallheaders') ? getallheaders() : [];

        // fallback
        if($key and !isset($headers[$key])) {
            return $fallback;
        }

        return $key ? $headers[$key] : $headers;
    }


    /**
     * Get IP
     * @return string
     */
    public static function ip()
    {
        return static::server('REMOTE_ADDR');
    }


    /**
     * Is local
     * @return bool
     */
    public static function local()
    {
        return in_array(static::ip(), ['127.0.0.1', '::1']);
    }


    /**
     * Get method
     * @return string
     */
    public static function method()
    {
        return static::server('REQUEST_METHOD', 'GET');
    }


    /**
     * Get asynchronous
     * @return bool
     */
    public static function async()
    {
        return static::server('HTTP_X_REQUESTED_WITH', false)
           and strtolower(static::server('HTTP_X_REQUESTED_WITH')) == 'xmlhttprequest';
    }


    /**
     * Is synchronous
     * @return bool
     */
    public static function sync()
    {
        return !static::async();
    }


    /**
     * Get browser name
     * @return string
     */
    public static function browser()
    {
        return get_browser()->browser;
    }


    /**
     * Is mobile
     * @return bool
     */
    public static function mobile()
    {
        return static::server('HTTP_X_WAP_PROFILE', false) or static::server('HTTP_PROFILE', false);
    }


    /**
     * Get last page visited
     * @return string
     */
    public static function from()
    {
        return static::server('HTTP_REFERER');
    }


    /**
     * Get elapsed system
     * @return float
     */
    public static function elapsed()
    {
        $start = static::server('REQUEST_TIME_FLOAT');
        $now = microtime(true);
        return number_format($now - $start, 4);
    }


    /**
     * Kupo !
     * @return string
     */
    public static function kupo()
    {
        $dialog = [
            'Kupo ?!',
            'I\'m hungry...',
            'May I help you ?',
            'It\'s dark in here...',
            'I haven\'t received any mail lately, Kupo.',
            'It\'s dangerous outside ! Kupo !',
            'Don\'t call me if you don\'t need me, Kupo !',
            'What do you want to do, Kupo ?'
        ];

        return 'o-&#949;(:o) ' . $dialog[array_rand($dialog)];
    }

}