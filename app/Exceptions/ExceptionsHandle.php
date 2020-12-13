<?php

namespace App\Exceptions;

use Steins\Exception\ExceptionHandleInterface;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class ExceptionsHandle implements ExceptionHandleInterface
{

    public function __construct($class)
    {

    }

    public function handle($exception)
    {
        // TODO: Implement handle() method.
    }
}