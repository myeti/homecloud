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

class Resolver implements ResolverInterface
{

    /** @var Injector */
    protected $injector;


    /**
     * Setup resolver
     * @param Injector $injector
     */
    public function __construct(Injector $injector = null)
    {
        $this->injector = $injector;
    }


    /**
     * Resolve input
     * @param $input
     * @return bool|Action
     */
    public function resolve($input)
    {
        return $this->resolveFunction($input)
            ?: $this->resolveStaticMethod($input)
            ?: $this->resolveInvokeMethod($input)
            ?: $this->resolveClassMethod($input);
    }


    /**
     * Resolve function callable
     * @param mixed $input
     * @return bool|Action
     */
    public function resolveFunction($input)
    {
        // check
        if(!($input instanceof \Closure) and !function_exists($input)) {
            return false;
        }

        // resolve
        $function = new \ReflectionFunction($input);
        $metadata = Annotation::get($function);

        // make action
        $action = new Action($input, $metadata);
        $action->type = 'function';

        return $action;
    }


    /**
     * Resolve static method callable
     * @param $input
     * @return bool|Action
     */
    public function resolveStaticMethod($input)
    {
        // parse string
        if(is_string($input) and strpos($input, '::') !== false) {
            $input = explode('::', $input);
        }

        // check
        if(is_callable($input) and is_array($input) and count($input) == 2) {
            $method = new \ReflectionMethod($input[0], $input[1]);
            if(!$method->isPublic() or !$method->isStatic()) {
                return false;
            }
        }
        else {
            return false;
        }

        // reflect class
        $class = new \ReflectionClass($input[0]);

        // get metadata
        $metadata = array_merge(
            Annotation::get($class),
            Annotation::get($method)
        );

        // make action
        $action = new Action($input, $metadata);
        $action->type = 'static-method';

        return $action;
    }


    /**
     * Resolve invoke method callable
     * @param mixed $input
     * @return bool|Action
     */
    public function resolveInvokeMethod($input)
    {
        // check
        if(!method_exists($input, '__invoke')) {
            return false;
        }

        // reflect class
        $class = new \ReflectionClass($input);

        // create object
        if(is_object($input)) {
            $object = $input;
        }
        else {
            $object = $this->injector ? $this->injector->make($input) : $class->newInstance();
        }

        // reflect method
        $method = new \ReflectionMethod($object, '__invoke');

        // get metadata
        $metadata = array_merge(
            Annotation::get($class),
            Annotation::get($method)
        );

        // set callable
        $callable = [$object, '__invoke'];

        // make action
        $action = new Action($callable, $metadata);
        $action->type = 'invoke-method';

        return $action;
    }


    /**
     * Resolve class method callable
     * @param $input
     * @return bool|Action
     */
    public function resolveClassMethod($input)
    {
        // parse string
        if(is_string($input) and strpos($input, '::') !== false) {
            $input = explode('::', $input);
        }

        // reflect tuple
        if(is_callable($input) and is_array($input) and count($input) == 2) {
            $method = new \ReflectionMethod($input[0], $input[1]);
            if(!$method->isPublic() and ($method->isStatic() or $method->isAbstract())) {
                return false;
            }
        }
        else {
            return false;
        }

        // reflect class
        $class = new \ReflectionClass($input[0]);
        $object = $this->injector ? $this->injector->make($input[0]) : $class->newInstance();

        // get metadata
        $metadata = array_merge(
            Annotation::get($class),
            Annotation::get($method)
        );

        // set callable
        $callable = [$object, $input[1]];

        // make action
        $action = new Action($callable, $metadata);
        $action->type = 'class-method';

        return $action;
    }


    /**
     * Quick resolving
     * @param $input
     * @param Injector $injector
     * @return bool|\Craft\Reflect\Action
     */
    public static function forge($input, Injector $injector = null)
    {
        $resolver = new self($injector);
        return $resolver->resolve($input);
    }

} 