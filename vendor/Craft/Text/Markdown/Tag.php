<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Text\Markdown;

use Craft\Text\Markdown;

abstract class Tag
{

    /** @var Markdown */
    protected $md;

    /**
     * Hard reference to md
     * @param Markdown $md
     */
    public function __construct(Markdown &$md)
    {
        $this->md = $md;
    }

    /**
     * Transform md to html tag
     * @param string $text
     * @return string
     */
    abstract public function transform($text);

} 