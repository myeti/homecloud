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

class LinkTag extends Tag
{

    /**
     * Transform md to html tag
     * @param string $text
     * @return string
     */
    public function transform($text)
    {
        $text = preg_replace('/([^!])\[(.+)\]\((.+) "(.+)"\)/', '$1<a href="$3" title="$4">$2</a>', $text);
        return preg_replace('/([^!])\[(.+)\]\((.+)\)/', '$1<a href="$3">$2</a>', $text);
    }

}