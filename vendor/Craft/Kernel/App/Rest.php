<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Kernel\App;

use Craft\Kernel\App;
use Craft\View\Json;

class Rest extends App
{

    /** @var array */
    protected $config = [
        'json.wrapper'  => null
    ];

    /**
     * Render data as json
     */
    protected function createView()
    {
        return new Json($this->config['json.wrapper']);
    }

} 