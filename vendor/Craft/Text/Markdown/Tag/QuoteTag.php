<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Text\Markdown\Tag;

use Craft\Text\Markdown\Tag;

class QuoteTag extends Tag
{

    /**
     * Transform md to html tag
     * @param string $text
     * @return string
     */
    public function transform($text)
    {
        $text = preg_replace_callback('/(\n>.*)+/', function($matches){

            // split
            $tab = preg_split('/\n/', $matches[0], -1, PREG_SPLIT_NO_EMPTY);

            // parse quote
            foreach($tab as $key => $line) {
                $tab[$key] = trim(preg_replace('/>(.*)/', "$1", $line));
            }

            // re process
            $quote = "<blockquote>\n\n" . implode("\n\n", $tab) . "\n<blockquote>";
            $quote = $this->md->perform($quote, ['quote']); // recursive perform

            return "\n" . $quote;

        }, $text);

        return $text;
    }

}