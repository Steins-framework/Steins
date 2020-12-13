<?php

namespace Steins\Application;

use Steins\Container\Container;

class Application extends Container
{
    protected string $path;

    protected static self $instance;

    public function __construct($path)
    {
        $this->path = $path;

        self::$instance = $this;

        $this->bindSelf();
    }

    public function path(): string
    {
        return $this->path;
    }

    public function bindSelf(){
        $this->singleton(self::class, $this);
    }

    public static function instance(): self
    {
        return self::$instance;
    }
}