<?php

namespace Steins\Router\Matcher;

use JetBrains\PhpStorm\ArrayShape;

class Matcher implements MatcherInterface
{
    protected static string $parmaReg = '/{(\w*?)}/';

    public function match($route, $uri): bool|array
    {
        if ($this->hasParameters($route)) {
            $params = $this->parseParameter($route);

            $regexp = $this->toRegexp($route, $params);

            preg_match($regexp, $uri, $matches);

            if (! empty($matches)){
                return array_intersect_key($matches, array_flip($params));
            }
        }
        elseif($route === $uri){
            return array();
        }

        return false;
    }

    public function hasParameters($uri): bool
    {
        return str_contains($uri, '{') && str_contains($uri,'}');
    }

    public function parseParameter($uri): array
    {
        preg_match_all(self::$parmaReg, $uri, $matches);

        return array_key_exists(1, $matches) ? $matches[1] : $matches;
    }

    public function toRegexp($uri, $params): string
    {
        foreach ($params as $key => $need) {
            $uri = str_replace("{{$need}}", "(?<{$need}>.*?)", $uri);
        }

        return '/' . $this->escape($uri) . '/';
    }

    public function escape($reg): string
    {
        return str_replace('/', '\/', $reg);
    }

}