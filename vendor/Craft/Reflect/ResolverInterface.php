<?php

namespace Craft\Reflect;

interface ResolverInterface
{

    /**
     * Resolve action
     * @param string $input
     * @return mixed
     */
    public function resolve($input);

} 