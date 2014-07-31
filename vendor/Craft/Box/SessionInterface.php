<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Box;

use Craft\Data\ProviderInterface;

interface SessionInterface extends ProviderInterface
{


    /**
     * Get session id
     * @return string
     */
    public function id();

}