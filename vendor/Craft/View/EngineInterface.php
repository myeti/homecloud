<?php

namespace Craft\View;

interface EngineInterface
{

    /**
     * Render template using data
     * @param string $template
     * @param array $data
     * @return string
     */
    public function render($template, $data = []);

} 