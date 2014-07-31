<?php

namespace Craft\Web\Form\Field;

use Forge\Engine;
use Craft\Web\Form\Field;

class Checkbox extends Field
{

    /**
     * Render input
     * @return string
     */
    public function input()
    {
        return Engine::render(dirname(__DIR__) . '/templates/checkbox.input', ['field' => $this]);
    }

} 