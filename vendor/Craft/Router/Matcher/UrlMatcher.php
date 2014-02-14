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

class UrlMatcher extends RegexMatcher
{

    /**
     * Find route
     * @param string $query
     * @param array $context
     * @param mixed $fallback
     * @return \Craft\Router\Route
     */
    public function find($query, array $context = [], $fallback = false)
    {
        // leading slash
        $query = '/' . ltrim($query, '/');
        return parent::find($query, $context, $fallback);
    }


    /**
     * Compile path into regex
     * @param $path
     * @return mixed|string
     */
    protected function compile($path)
    {
        // leading slash
        $pattern = '/' . ltrim($path, '/');

        // compile pattern
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = preg_replace('#\:(\w+)#', '(?P<arg__$1>(.+))', $pattern);
        $pattern = preg_replace('#\+(\w+)#', '(?P<env__$1>(.+))', $pattern);
        $pattern = '#^' . $pattern . '$#';

        return $pattern;
    }


    /**
     * Parse results
     * @param array $results
     * @return array
     */
    protected function parse(array $results)
    {
        // default values
        $data = [
            'args' => [],
            'envs' => []
        ];

        // parse
        foreach($results as $key => $value) {
            if(substr($key, 0, 5) == 'arg__' or substr($key, 0, 5) == 'env__') {
                $group = substr($key, 0, 3) . 's';
                $label = substr($key, 5);
                $data[$group][$label] = $value;
            }
        }

        return $data;
    }


}