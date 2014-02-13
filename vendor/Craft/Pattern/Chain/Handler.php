<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Pattern\Chain;

interface Handler
{

    /**
     * Get handler name
     * @return string
     */
    public function name();


    /**
     * Take, proceed and return input
     * @param Material $material
     * @return Material
     */
    public function handle(Material $material);

} 