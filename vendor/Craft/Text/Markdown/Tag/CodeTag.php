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

class CodeTag extends Tag
{

    /**
     * Transform md to html tag
     * @param string $text
     * @return string
     */
    public function transform($text)
    {
        // pre code
        $text = preg_replace_callback('/(\n[ ]{4,}.*)+/', function($matches){
            return "<pre><code>" . $matches[0] . "\n</code></pre>\n";
        }, $text);

        $text = preg_replace('/`{3,}(.+)?\n(.+\n)*`{3,}/', "<pre><code lang=\"$1\">\n$2</code></pre>\n", $text);

        // inline code
        $text = preg_replace('/`(.+)`/', '<code>$1</code>', $text);

        return $text;
    }

}