<?php

namespace Steins\Container;

use ReflectionClass;
use Closure;
use BadMethodCallException;
use ReflectionNamedType;

class Container
{

    protected array $bindings = array();

    protected array $shared = array();

    protected array $singleton = array();

    public function singleton($abstract, $concrete)
    {
        $this->bind($abstract, $concrete, true);

        if (is_object($concrete)){
            $this->singleton[$abstract] = $concrete;
        }
    }

    public function bind($abstract, $concrete, $single = false)
    {
        $this->bindings[$abstract] = $concrete;
        if ($single){
            $this->shared[$abstract] = true;
        }
    }

    public function bindMany(array $bindings, $single = false){
        $this->bindings = array_merge($this->bindings, $bindings);
        if ($single){
            foreach ($bindings as $abstract => $_){
                $this->shared[$abstract] = true;
            }
        }
    }

    public function exec(string | Closure $function, array $context = [])
    {
        if ($function instanceof Closure){
            return $function(...$context);
        }

        if (is_string($function) && str_contains($function, '@')){
            [$class, $method] = explode('@', $function);

            $class = $this->resolve($class, $context);

            return $this->resolveAction($class, $method, $context);
        }

        throw new \Exception("Unsupported type: {$function}");
    }

    public function make(string | Closure $abstract, array $context = array())
    {
        return $this->resolve($abstract, $context);
    }

    public function resolve(string | object $abstract, array $context = array())
    {
        /**
         * todo 创建出一个实例之后，如果这个实例是单例，需要添加到已解决数组中
         */
        try {
            if (is_string($abstract)){

                $ref = new ReflectionClass($abstract);
            }
            elseif ($abstract instanceof ReflectionClass){
                $ref = $abstract;
            }
            else{
                return $abstract;
            }

            if ($this->resolved($ref->getName())){
                return $this->singleton[$ref->getName()];
            }

            if (! $ref->isInstantiable()){
                if (null !== $concrete = $this->findConcrete($abstract)){
                    return $this->resolve($concrete, $context);
                }

                throw new \Exception("Unable to resolve the dependency of [{$ref->getName()}]");
            }

            $constructor = $ref->getConstructor();

            if ($constructor === null){
                return $ref->newInstance();
            }

            $dependencies = array();

            foreach ($constructor->getParameters() as $parameter){
                $dependClass = $parameter->getClass();

                if ($dependClass === null){
                    if (array_key_exists($parameter->getName(), $context)){
                        $dependencies[] = $context[$parameter->getName()];
                        continue;
                    }

                    if ($parameter->isDefaultValueAvailable()){
                        $dependencies[] = $parameter->getDefaultValue();
                        continue;
                    }

                    throw new \Exception("Unable to resolve the dependency of [{$ref->getName()}]: {$parameter->getName()}");
                }

                $dependencies[] = $this->resolve($dependClass);
            }
            return $ref->newInstanceArgs($dependencies);

        } catch (\ReflectionException $e) {
            dd('Exception', $e);
        }
    }

    public function resolveAction(object $instance, string $method, array $context = [])
    {
        $ref = new ReflectionClass($instance);
        try {
            $refMethod = $ref->getMethod($method);
            $dependent = array();

            foreach ($refMethod->getParameters() as $parameter){
                if ($parameter->allowsNull()){
                    continue;
                }
                /** @var ReflectionNamedType $refType */
                $refType = $parameter->getType();
                if (array_key_exists($parameter->getName(), $context)){
                    $dependent[] = $context[$parameter->getName()];
                    continue;
                }

                if ($refType !== null && $refType->isBuiltin()){
                    $dependent[] = $this->resolve($refType->getName());
                    continue;
                }
                throw new \ArgumentCountError("The required parameter [{$parameter->getName()}] is missing when calling the method");
            }
            return $refMethod->invoke($instance, ...$dependent);

        }catch (\ReflectionException $e) {
            throw new BadMethodCallException("Call a method that does not exist {$ref->getName()}::[$method]");
        }

    }

    public function resolved($abstract): bool
    {
        return array_key_exists($abstract, $this->shared)
            && array_key_exists($abstract, $this->singleton);
    }

    public function ResolveDependencies(){

    }

    public function findConcrete($abstract)
    {
        $abstract = $abstract instanceof ReflectionClass ? $abstract->getName(): $abstract;

        if (array_key_exists($abstract, $this->bindings)){
            return $this->bindings[$abstract];
        }

        return  null;
    }
}