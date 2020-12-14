<?php


define('STEINS_START', microtime(true));

require_once __DIR__ . '/../vendor/autoload.php';

/** @var Steins\Application\Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';


/** @var App\Http\Kernel $kernel */
$kernel = $app->make(
    \Steins\Kernel\KernelInterface::class
);

$response = $kernel->handle(
    Steins\Http\Request\Request::capture()
);

$response->send();