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

class FtpContext implements StreamContext
{

    /** @var bool */
    public $secure = false;

    /** @var bool */
    public $overwrite = false;

    /** @var int */
    public $resume_pos = 0;

    /** @var string */
    public $proxy;


    /**
     * Prepare opts
     * @return array
     */
    public function opts()
    {
        // define wrapper
        $wrapper = $this->secure ? 'ftps' : 'ftp';

        // build opts
        $opts = [
            $wrapper => [
                'overwrite'    => $this->overwrite,
                'resume_pos'   => $this->resume_pos,
            ]
        ];

        // proxy
        if($this->proxy) {
            $opts[$wrapper]['proxy'] = $this->proxy;
        }

        return $opts;
    }

}