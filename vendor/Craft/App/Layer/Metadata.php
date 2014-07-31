<?php

namespace Craft\App\Layer;

use Craft\App\Layer;
use Craft\App\Request;
use Craft\Reflect\Resolver;
use Craft\Reflect\ResolverInterface;
use Forge\Logger;

/**
 * Resolve action and read metadata.
 */
class Metadata extends Layer
{

    /** @var ResolverInterface */
    protected $resolver;


    /**
     * Set resolver
     * @param ResolverInterface $resolver
     */
    public function __construct(ResolverInterface $resolver = null)
    {
        $this->resolver = $resolver ?: new Resolver;
    }


    /**
     * Handle request
     * @param Request $request
     * @return Request
     */
    public function before(Request $request)
    {
        // resolve
        $parsed = $this->resolver->resolve($request->action);

        // update request
        $request->action = $parsed->callable;
        $request->meta = array_merge($request->meta, $parsed->meta);

        Logger::info('App.Metadata : request metadata parsed');

        return $request;
    }

}