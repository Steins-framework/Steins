<?php

namespace Steins\Reflection;

class Reflection
{
    protected static array $cache;

    static function formClass(string $class, bool $use_cache = true)
    {
        if ($use_cache && array_key_exists($class, self::$cache)){
            return self::$cache[$class];
        }

        return self::create();
    }

    static function formMethod($method){

    }

    protected static function create(){

    }

}