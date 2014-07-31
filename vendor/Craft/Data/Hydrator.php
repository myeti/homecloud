<?php

namespace Craft\Data;

trait Hydrator
{

    /**
     * Hydrate properties from array
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        foreach($data as $prop => $value) {
            if(property_exists($this, $prop)) {
                $this->{$prop} = $value;
            }
        }
    }

} 