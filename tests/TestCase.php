<?php

namespace Tests;

use Steins\Application\Application;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public Application $app;


    public function createApplication(): Application
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }
}