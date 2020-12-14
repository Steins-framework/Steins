<?php

namespace Steins\Configuration;

interface ConfigInstance
{
    public function get(string $key = null, $default = null):mixed;

    public function set(string $key, $value):void;
}