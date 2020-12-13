<?php

$app = new \Steins\Application\Application(
    realpath(__DIR__ . '/../')
);

$app->singleton(\Steins\Kernel\KernelInterface::class, \App\Http\Kernel::class);

$app->singleton(\Steins\Router\RouterInterface::class, \Steins\Router\Router::class);

$app->singleton(\Steins\Http\Response\ResponseInterface::class, \Steins\Http\Response\Response::class);

$app->singleton(\Steins\Configuration\ConfigInstance::class, \Steins\Configuration\Configuration::class);

return $app;
