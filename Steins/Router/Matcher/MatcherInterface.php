<?php

namespace Steins\Router\Matcher;

interface MatcherInterface
{
    public function match($route, $uri): bool|array;
}