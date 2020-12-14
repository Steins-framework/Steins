<?php

namespace Steins\Http\Request;

use Steins\Singleton\Singleton;

class Request implements RequestInterface{
    use Singleton;

    protected array $server;

    protected string $uri;

    protected function __construct(){}

    public static function capture(): RequestInterface
    {
        self::setInstance(new self());

        return self::getInstance()->lazyLoad();
    }

    public function lazyLoad(): self
    {
        $this->server = $_SERVER;

        return $this;
    }

    public function load()
    {

    }

    public function uri()
    {
        if (empty($this->uri)){
            $this->uri = $this->server['REQUEST_URI'];
            if (str_contains($this->uri, '?')){
                $this->uri = explode('?', $this->uri)[0];
            }
        }
        return $this->uri;
    }

    public function method(): string
    {
        return $this->server['REQUEST_METHOD'];
    }
}
