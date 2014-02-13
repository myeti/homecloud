<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Reflect;

abstract class Object
{

    /**
     * Get class name
     * @param $object
     * @return mixed
     */
    public static function classname($object)
    {
        $segments = explode('\\', get_class($object));
        return end($segments);
    }


    /**
     * Check if object is using trait
     * @param $object
     * @param $trait
     * @return bool
     */
    public static function hasTrait($object, $trait)
    {
        $traits = class_uses($object);
        return in_array($trait, $traits);
    }


    /**
     * Hydrate object props with array
     * @param $object
     * @param array $data
     * @return object
     */
    public static function hydrate($object, array $data)
    {
        foreach($data as $field => $value) {
            if(property_exists($object, $field)) {
                $object->{$field} = $value;
            }
        }

        return $object;
    }


    /**
     * return constructed object for chaining
     * @param $object
     * @return mixed
     */
    public static function with($object)
    {
        return $object;
    }


    /**
     * Check if object is instance of classname
     * @param $object
     * @param string $classname
     * @return bool
     */
    public static function is($object, $classname)
    {
        return ($object instanceof $classname);
    }

} 