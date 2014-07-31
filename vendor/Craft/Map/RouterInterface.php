<?php

namespace Craft\Map;

interface RouterInterface
{

    /**
     * Add route
     * @param Route $route
     */
    public function add(Route $route);

    /**
     * Find route
     * @param string $query
     * @param array $context
     * @return Route
     */
    public function find($query, array $context = []);

}