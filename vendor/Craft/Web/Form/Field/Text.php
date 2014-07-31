<?php

namespace Craft\Web\Form\Field;

use Forge\Engine;
use Craft\Web\Form\Field;

class Text extends Field
{

    /**
     * Render input
     * @return string
     */
    public function input()
    {
        return Engine::render(dirname(__DIR__) . '/templates/text.input', ['field' => $this]);
    }

} 