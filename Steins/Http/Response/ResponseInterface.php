<?php

namespace Steins\Http\Response;

interface ResponseInterface
{

    public function __construct(mixed $content);


    public function create(mixed $content): self;

    public function createFormString(string $content): self;

    public function createFormArray(array $content):self;

    public function createFormObject($content):self;

    public function addHeader(string $key, string $value, bool $hood = true);

    public function send();

    public function __toString();
}
