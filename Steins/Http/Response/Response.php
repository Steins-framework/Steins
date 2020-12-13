<?php

namespace Steins\Http\Response;

class Response implements ResponseInterface
{
    protected array $header = array();
    protected string $content;

    public function __construct(mixed $content = null)
    {
        if ($content !== null){
            $this->create($content);
        }

    }

    public function create(mixed $content): self
    {
        switch (gettype($content)){
            case 'NULL':
                return $this;
            case 'array':
                return $this->createFormArray($content);
            case 'object':
                return $this->createFormObject($content);
            default:
                return $this->createFormString($content);
        }
    }

    public function createFormString(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function createFormArray(array $content):self
    {
        $this->addHeader('content-type','application/json; charset=utf-8');

        $this->content = json_encode($content);
        return $this;
    }

    public function createFormObject($content):self
    {
        return $this;
    }

    public function addHeader(string $key, string $value, bool $hood = true)
    {
        if ($hood && array_key_exists($key, $this->header)){
            if (is_array($this->header[$key])){
                $this->header[$key][] = $value;
            }else{
                $this->header[$key] = [
                    $this->header[$key],$value
                ];
            }
            return;
        }
        $this->header[$key] = $value;
    }

    public function send()
    {
        foreach ($this->header as $key => $value){
            if (is_array($value)){
                foreach ($value as $item){
                    if (is_string($item)){
                        header($key, $item);
                    }
                }
                continue;
            }
            header($key, $value);
        }
        echo $this->__toString();
    }

    public function __toString():string
    {
        return $this->content;
    }
}