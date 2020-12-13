<?php

namespace Steins\Router\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class RouteGroup
{
    protected string $prefix;

    protected array $routeMap = array();

    protected array $middleware = array();

    public function __construct(string $prefix, array $middleware = [])
    {
        $this->prefix = $prefix;
        $this->middleware = $middleware;
    }

    public function prefix(): string
    {
        return $this->prefix;
    }

    public function setPrefix(string $prefix): self
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * @return Route[]
     */
    public function routes(): array
    {
        return $this->routeMap;
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

    public function clearMiddleware(): self
    {
        $this->middleware = [];
        return $this;
    }

    public function addRoute(Route $route)
    {
        $this->routeMap[] = $route;
    }

    public function isEmpty():bool
    {
        return empty($this->routeMap);
    }
}