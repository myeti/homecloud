<?php

namespace Craft\Web;

$form = new Form('/action', 'post');

$form->add(
    new Form\Field\String('name', 'value', 'placeholder')
);


class Model
{

    /**
     * @var Foo[]
     * @form checkboxes
     */
    public $foos = [];

}