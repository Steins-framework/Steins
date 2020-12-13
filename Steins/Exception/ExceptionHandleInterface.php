<?php

namespace Steins\Exception;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
interface ExceptionHandleInterface
{
    public function handle($exception);
}