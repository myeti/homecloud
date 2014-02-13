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

abstract class SourceDict implements Source
{

    /** @var array */
    protected $dict = [];

    /**
     * Generate random text
     * @param int $words
     * @param int $lines
     * @param int $texts
     * @return string
     */
    public function generate($words, $lines, $texts)
    {
        $output = '';

        for($i = 1; $i <= $texts; $i++) {
            for($j = 1; $j <= $lines; $j++) {
                $line = implode(' ', array_rand($this->dict, $words));
                $output .= ucfirst($line) . '. ';
            }
            $output .= "\n";
        }

        return rtrim($output, "\n");
    }

}