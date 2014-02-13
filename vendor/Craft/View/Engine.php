<?php

namespace Craft\View;

interface Engine
{

    /**
     * Render data with resource
     * @param $something
     * @return mixed
     */
    public function render($something);

} 