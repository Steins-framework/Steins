<?php

return [
    'singleton-realization' => [
        \Steins\Router\Matcher\MatcherInterface::class => \Steins\Router\Matcher\Matcher::class,
        \Steins\Router\Scanner\ScannerInterface::class => \Steins\Router\Scanner\Scanner::class,
    ]
];