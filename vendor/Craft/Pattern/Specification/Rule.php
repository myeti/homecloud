<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Pattern\Specification;

abstract class Rule
{

    /** @var string[] */
    public $errors = [];


    /**
     * Apply rule matching and return report
     * @param Item $item
     * @return Report
     */
    public function apply(Item $item)
    {
        // clean errors
        $this->errors = [];

        // run rule matching
        $pass = $this->match($item);

        // make report
        $report = new Report();
        $report->item = $item;
        $report->pass = $pass;
        $report->errors = $this->errors;

        // clean errors
        $this->errors = [];

        return $report;
    }


    /**
     * Check if this match the rule
     * @param Item $item
     * @return bool
     */
    abstract protected function match(Item $item);


    /**
     * Add error
     * @param $message
     */
    protected function error($message)
    {
        $this->errors[] = $message;
    }

} 