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

class ListTag extends Tag
{

    /**
     * Transform md to html tag
     * @param string $text
     * @return string
     */
    public function transform($text)
    {
        $text = $this->ul($text);
        $text = $this->ol($text);

        return $text;
    }


    /**
     * Transform UL
     * @param string $text
     * @return string
     */
    protected function ul($text)
    {
        // ul *
        $text = preg_replace_callback('/(\n\* .+)+/', function($matches){

            // split
            $tab = preg_split('/\n/', $matches[0], -1, PREG_SPLIT_NO_EMPTY);

            // make ul
            $list = "<ul>";
            foreach($tab as $li) {
                $list.= "\n<li>" . preg_replace('/\* (.+)/', '$1', $li) . "</li>";
            }
            $list .= "\n</ul>\n";

            return $list;

        }, $text);

        // ul -
        $text = preg_replace_callback('/((\n|^)[ ]*- .+)+/', function($matches) {

            // split
            $tab = preg_split('/\n/', $matches[0], -1, PREG_SPLIT_NO_EMPTY);
            $stuck = $level = null;

            // make ul
            $list = "<ul>";
            foreach($tab as $i => $li) {

                // get spaces
                $spaces = strlen(preg_replace('/([ ]*)- .+/', '$1', $li));
                if($spaces > 0) {
                    $spaces /= 4;
                }

                // get level
                if($i == 0) {
                    $level = $spaces;
                }

                // inner tab
                if($spaces > $level) {
                    if($stuck == null) {
                        $stuck = $i;
                        $tab[$stuck] = $li;
                    }
                    else {
                        $tab[$stuck] .= "\n" . $li;
                        unset($tab[$i]);
                    }
                }
                elseif($stuck != null) {
                    $list .= "\n" . str_repeat("\t", $level) . "<li>\n" . $this->apply('ul-alt', $tab[$stuck]) . '</li>';
                    $stuck = null;
                }
                else {
                    $list.= "\n" . str_repeat("\t", $level) . "<li>" . preg_replace('/[ ]*- (.+)/', '$1', $li) . "</li>";
                }

            }
            $list .= "\n</ul>\n";

            return $list;

        }, $text);

        return $text;
    }


    /**
     * Transform OL
     * @param string $text
     * @return string
     */
    public function ol($text)
    {
        return preg_replace_callback('/(\n[0-9]+\. .+\n)+/', function($matches){

            // split
            $tab = preg_split('/\n/', $matches[0], -1, PREG_SPLIT_NO_EMPTY);

            // make ul
            $list = "<ol>";
            foreach($tab as $li) {
                $list.= "\n<li>" . preg_replace('/[0-9]+\. (.+)/', '$1', $li) . "</li>";
            }
            $list .= "\n</ol>\n";

            return $list;

        }, $text);
    }

}