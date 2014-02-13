<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Data;

class Paginator extends \ArrayObject
{

    /** @var int */
    public $size;

    /** @var int */
    public $page;

    /** @var int */
    public $max;

    /** @var int */
    public $total;

    /** @var int */
    public $from;

    /** @var int */
    public $to;

    /** @var int */
    public $prev;

    /** @var int */
    public $next;


    /**
     * Setup paginator
     * @param array $data
     * @param int $size
     * @param int $page
     * @param int $total
     */
    public function __construct(array $data = [], $size, $page, $total)
    {
        // clean
        $this->size = (int)$size;
        $this->page = (int)$page;
        $this->total = (int)$total;

        // get max
        $this->max = ceil($this->total / $this->size);

        // page boundaries
        if($this->page < 1) {
            $this->page = 1;
        }
        elseif($this->page > $this->max) {
            $this->page = $this->max;
        }

        // from & to
        $this->from = ($this->size * ($this->page - 1)) + 1;
        $this->to = $this->from + $this->size;

        // next & prev
        $this->prev = ($this->page > 1) ? $this->page - 1 : false;;
        $this->next = ($this->page < $this->max) ? $this->page + 1 : false;

        // inject data
        parent::__construct($data);
    }

} 