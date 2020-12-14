<?php

namespace Steins\Router\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Route
{
    protected string $uri;

    protected string $prefix;

    protected string $handle;

    protected array $methods;

    protected array $context;

    protected array $middleware = array();

    public function __construct(string $uri, array $methods = ['GET'], array $middleware = [], array $context = [])
    {
        $this->uri = $uri;

        $this->methods = $methods;

        $this->middleware = $middleware;

        $this->context = $context;
    }

    public function middleware(): array
    {
        return $this->middleware;
    }

    public function addMiddleware($middleware):self
    {
        if (! in_array($middleware,$this->middleware)){
            $this->middleware[] = $middleware;
        }
        return $this;
    }

    public function addMiddlewares(array $middlewares)
    {
        $this->middleware = array_flip(array_flip(
            array_merge($this->middleware, $middlewares)
        ));
    }

    public function context(): array
    {
        return $this->context;
    }

    public function addContexts(array $context){
        $this->context = array_merge($this->context, $context);
    }

    public function clearMiddleware(): self
    {
        $this->middleware = [];
        return $this;
    }

    public function uri(): string
    {
        return $this->uri;
    }

    public function handle(): string | \Closure
    {
        return $this->handle;
    }

    public function setHandle(string $handle)
    {
        $this->handle = $handle;
    }

    public function setPrefix(string $prefix)
    {
        $this->prefix = $prefix;
    }

    public function methods()
    {
        $this->methods;
    }

    public function methodContain($method): bool
    {
        return in_array($method, $this->methods);
    }
}