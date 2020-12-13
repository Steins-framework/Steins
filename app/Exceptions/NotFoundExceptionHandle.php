<?php

namespace App\Exceptions;

use Steins\Exception\ExceptionHandleInterface;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class NotFoundExceptionHandle implements ExceptionHandleInterface
{
    public function handle($exception)
    {
        dd('Yeah!');
    }
}