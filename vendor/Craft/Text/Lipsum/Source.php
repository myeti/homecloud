<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Text\Lipsum;

interface Source
{

    /**
     * Generate random text
     * @param int $words
     * @param int $lines
     * @param int $texts
     * @return string
     */
    public function generate($words, $lines, $texts);

} 