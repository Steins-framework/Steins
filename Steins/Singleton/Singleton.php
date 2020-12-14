<?php

namespace Steins\Singleton;

trait Singleton
{
    protected static self|null $instance = null;

    public static function getInstance(): self
    {
        if (self::$instance === null){
            self::$instance = new self();
        }

        return self::$instance;
    }

    protected static function setInstance($instance): void
    {
        self::$instance = $instance;
    }
}