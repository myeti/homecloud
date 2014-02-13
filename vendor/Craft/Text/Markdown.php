<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Text;

use Craft\Text\Markdown\Tag;

class Markdown
{

    /** @var Tag[] */
    protected $tags = [];


    /**
     * Setup basic tags
     */
    public function __construct()
    {
        $this->tags = [
            'title'     => new Tag\TitleTag($this),
            'list'      => new Tag\ListTag($this),
            'code'      => new Tag\CodeTag($this),
            'quote'     => new Tag\QuoteTag($this),
            'paragraph' => new Tag\ParagraphTag($this),
            'link'      => new Tag\LinkTag($this),
            'image'     => new Tag\ImageTag($this),
            'style'     => new Tag\StyleTag($this),
        ];
    }


    /**
     * Add tag transformation
     * @param $name
     * @param Tag $tag
     */
    public function add($name, Tag $tag)
    {
        $this->tags[$name] = $tag;
    }


    /**
     * Remove rule
     * @param string $name
     */
    public function drop($name)
    {
        unset($this->tags[$name]);
    }


    /**
     * Transform markdown text to html
     * @param string $text
     * @param array $skip
     * @return string
     */
    public function perform($text, $skip = [])
    {
        // clean
        $text = trim($text);

        // apply all rules
        foreach($this->tags as $name => $tag) {

            // skip ?
            if(in_array($name, $skip)) {
                continue;
            }

            $text = $tag->transform($text);
        }

        return $text;
    }

}