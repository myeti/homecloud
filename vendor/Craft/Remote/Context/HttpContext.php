<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Remote\Context;

use Craft\Remote\StreamContext;

class HttpContext implements StreamContext
{

    /** @var bool */
    public $secure = false;

    /** @var string */
    public $method;

    /** @var string */
    public $header;

    /** @var string */
    public $user_agent;

    /** @var array */
    public $content = [];

    /** @var string */
    public $proxy;

    /** @var bool */
    public $request_fulluri = false;

    /** @var int */
    public $follow_location = 1;

    /** @var int */
    public $max_redirects = 20;

    /** @var float */
    public $protocol_version = 1.0;

    /** @var float */
    public $timeout;

    /** @var bool */
    public $ignore_errors = false;


    /**
     * Prepare opts
     * @return array
     */
    public function opts()
    {
        // define wrapper
        $wrapper = $this->secure ? 'https' : 'http';

        // build opts
        $opts = [
            $wrapper => [
                'method'            => strtoupper($this->method),
                'header'            => implode("\r\n", $this->header),
                'request_fulluri'   => $this->request_fulluri,
                'follow_location'   => $this->follow_location,
                'max_redirects'     => $this->max_redirects,
                'protocol_version'  => $this->protocol_version,
                'ignore_errors'     => $this->ignore_errors
            ]
        ];

        // content
        if($this->content) {
            $opts[$wrapper]['content'] = http_build_query($this->content);
        }

        // proxy
        if($this->proxy) {
            $opts[$wrapper]['proxy'] = $this->proxy;
        }

        // timeout
        if($this->timeout) {
            $opts[$wrapper]['timeout'] = $this->timeout;
        }

        return $opts;
    }

}