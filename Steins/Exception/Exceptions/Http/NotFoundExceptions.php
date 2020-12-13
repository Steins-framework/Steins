<?php

namespace Steins\Exception\Exceptions\Http;

use Throwable;

class NotFoundExceptions extends HttpExceptions
{
    public function __construct($message = "404 NotFound", $code = 404)
    {
        parent::__construct($message, $code);
    }
}