<?php

namespace Steins\Router;

use Steins\Router\Attribute\Route;
use Steins\Router\Matcher\MatcherInterface;
use Steins\Router\Scanner\ScannerInterface;

interface RouterInterface
{
    public function match($uri): bool|array;

    public function scan();
}