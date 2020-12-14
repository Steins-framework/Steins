<?php

namespace Steins\Http\Request;

interface RequestInterface
{
    static function capture(): self;

    public function lazyLoad();

    public function load();

    public function uri();

    public function method(): string;
}