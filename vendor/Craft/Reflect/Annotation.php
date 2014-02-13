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

abstract class Annotation
{

    /**
     * Get object annotations
     * @param $class
     * @param string|null $annotation
     * @return array|string|null
     */
    public static function object($class, $annotation = null)
    {
        $ref = is_object($class) ? new \ReflectionObject($class) : new \ReflectionClass($class);
        return static::get($ref, $annotation);
    }


    /**
     * Get method annotations
     * @param $class
     * @param $method
     * @param $annotation
     * @return array|null
     */
    public static function method($class, $method, $annotation = null)
    {
        $ref = new \ReflectionMethod($class, $method);
        return static::get($ref, $annotation);
    }


    /**
     * Get property annotations
     * @param $class
     * @param $property
     * @param null $annotation
     * @return array|null
     */
    public static function property($class, $property, $annotation = null)
    {
        $ref = new \ReflectionProperty($class, $property);
        return static::get($ref, $annotation);
    }


    /**
     * Get annotation from Reflector object
     * @param $ref
     * @param $annotation
     * @return array|null
     */
    public static function get(\Reflector $ref, $annotation = null)
    {
        // cannot read phpdoc
        if(!method_exists($ref, 'getDocComment')) {
            return false;
        }

        // parse @annotation
        preg_match_all('/@([a-zA-Z0-9]+) ([a-zA-Z0-9._\-\/ ]+)/', $ref->getDocComment(), $out, PREG_SET_ORDER);

        // sort
        $data = [];
        foreach($out as $match) {
            $data[$match[1]] = $match[2];
        }

        // return all or one annotation
        if($annotation) {
            return isset($data[$annotation]) ? $data[$annotation] : null;
        }

        return $data;
    }

}