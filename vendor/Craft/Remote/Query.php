<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Remote;

abstract class Query
{

    /**
     * Send request
     * @param string $url
     * @param array $opts
     * @return mixed
     */
    public static function make($url, array $opts = [])
    {
        $context = stream_context_create($opts);
        return file_get_contents($url, null, $context);
    }


    /**
     * Make get request
     * @param string $url
     * @param array $params
     * @return mixed
     */
    public static function get($url, array $params = [])
    {
        // default opts
        $opts = [
            'http' => [
                'method'            => 'get',
                'request_fulluri'   => false,
                'follow_location'   => 1,
                'max_redirects'     => 20,
                'protocol_version'  => 1.0,
                'ignore_errors'     => false
            ]
        ];

        // build query
        if($params) {
            $url .= '?' . http_build_query($params);
        }

        return static::make($url, $opts);
    }


    /**
     * Make post request
     * @param $url
     * @param array $data
     * @return mixed
     */
    public static function post($url, array $data = [])
    {
        // default opts
        $opts = [
            'http' => [
                'method'            => 'post',
                'request_fulluri'   => false,
                'follow_location'   => 1,
                'max_redirects'     => 20,
                'protocol_version'  => 1.0,
                'ignore_errors'     => false,
                'content'           => http_build_query($data)
            ]
        ];

        return static::make($url, $opts);
    }

} 