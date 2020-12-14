<?php

namespace Steins\Exception\Exceptions\Http;

use Exception;
use Throwable;

class HttpExceptions extends Exception
{
    public function __construct($message = "", $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}