<?php

namespace Steins\Kernel;

use Steins\Http\Request\RequestInterface;
use Steins\Http\Response\ResponseInterface;

interface KernelInterface
{
    public function boot(): void;

    public function Handle(RequestInterface $request): ResponseInterface;
}