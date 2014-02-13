<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Router\Matcher;

use Craft\Router\Matcher;
use Craft\Router\Route;

abstract class RegexMatcher extends Matcher
{

    /**
     * Find route
     * @param string $query
     * @param array $context
     * @param mixed $fallback
     * @return Route
     */
    public function find($query, array $context = [], $fallback = false)
    {
        // search in all routes
        foreach($this->router->all() as $route)
        {
            // compile pattern
            $pattern = $this->compile($route->path);

            // compare
            if(preg_match($pattern, $query, $out)){

                // strip first line
                unset($out[0]);

                // check context
                if(array_intersect_assoc($context, $route->context) != $route->context) {
                    continue;
                }

                // parse data
                $data = $this->parse($out);

                $route->data = $data;
                return $route;
            }
        }

        return $fallback;
    }


    /**
     * Compile path into regex
     * @param $path
     * @return string
     */
    abstract protected function compile($path);


    /**
     * Parse results
     * @param array $results
     * @return array
     */
    abstract protected function parse(array $results);


}