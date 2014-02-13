<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Router;

abstract class Matcher
{

    /** @var RouteProvider */
    protected $router;


    /**
     * Setup matcher with router
     * @param RouteProvider $router
     */
    public function __construct(RouteProvider &$router)
    {
        $this->router = $router;
    }


    /**
     * Get router
     * @return RouteProvider
     */
    public function &router()
    {
        return $this->router;
    }


    /**
     * Find route
     * @param string $query
     * @param array $context
     * @param mixed $fallback
     * @return Route
     */
    abstract public function find($query, array $context = [], $fallback = null);

} 