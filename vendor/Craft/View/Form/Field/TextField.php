<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\View\Form\Field;

use Craft\View\Form\Field;

class TextField extends Field
{

    /**
     * Render input only
     * @return string
     */
    public function input()
    {
        return $this->render('text.input');
    }

}