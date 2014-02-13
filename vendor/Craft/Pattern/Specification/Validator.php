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

class Validator extends Rule
{

    /** @var Rule[] */
    protected $rules = [];

    /**
     * Check if rule exists
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->rules[$name]);
    }

    /**
     * Set a rule
     * @param string $name
     * @param Rule $rule
     * @return $this
     */
    public function set($name, Rule $rule)
    {
        $this->rules[$name] = $rule;
        return $this;
    }

    /**
     * Drop rule
     * @param string $name
     * @return $this
     */
    public function drop($name)
    {
        unset($this->rules[$name]);
        return $this;
    }

    /**
     * Check if this item match all the rule
     * @param Item $item
     * @return bool
     */
    protected function match(Item $item)
    {
        $pass = true;

        foreach($this->rules as $rule) {
            $report = $rule->apply($item);
            $pass &= $report->pass;
            $this->errors = $report->errors + $this->errors;
        }

        return $pass;
    }

}