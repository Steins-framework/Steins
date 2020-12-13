<?php

namespace Steins\Kernel;

use JetBrains\PhpStorm\ArrayShape;
use Steins\Application\Application;
use Steins\Configuration\ConfigInstance;
use Steins\Exception\ExceptionHandleInterface;
use Steins\Exception\Exceptions\Http\NotFoundExceptions;
use Steins\Http\Request\RequestInterface;
use Steins\Http\Response\ResponseInterface;
use Steins\Router\Attribute\Route;
use Steins\Router\RouterInterface;
use Exception;

class Kernel implements KernelInterface
{
    protected Application $app;

    protected RouterInterface $router;

    protected ConfigInstance $config;

    public function __construct(Application $app, ConfigInstance $config)
    {
        $this->app = $app;

        $this->config = $config;

        $this->boot();
    }

    public function Handle(RequestInterface $request): ResponseInterface
    {
        $route = $this->chooseRoute($request->uri(), $request->method());
        if ($route === null){
            throw new NotFoundExceptions();
        }
        /** @var ResponseInterface $response */
        $response = $this->app->make(ResponseInterface::class);

        try {
            $response->create(
                $this->app->exec($route->handle(), $route->context())
            );
        }catch (\Exception $exception){
            $this->controllerException($exception, $route->handle());
        }

        return $response;
    }

    public function controllerException(Exception $exception, string $controller)
    {
        if (! str_contains($controller, '@')){
            throw $exception;
        }
        list($class, $method) = explode('@', $controller);

        try {
            $refController = new \ReflectionClass($class);
            $refMethod = $refController->getMethod($method);

            $attributes = $refMethod->getAttributes();

            foreach ($attributes as $attribute){
                if (! str_contains($attribute->getName(), 'Exception')){
                    continue;
                }

                $refException = new \ReflectionClass($attribute->getName());

                $interfaces = $refException->getInterfaceNames();
                if (in_array(ExceptionHandleInterface::class, $interfaces)){
                    dd($attribute->getArguments());
                    $exceptionHandle = $this->app->make($refException, $attribute->getArguments());
                    dd($exceptionHandle);
                }
            }

        } catch (\ReflectionException $e) {
            throw $exception;
        }
    }

    protected function chooseRoute($uri, string $method): Route | null
    {
        $routes = $this->router->match($uri);
        foreach ($routes as $route){
            if ($route->methodContain($method)) {
                return $route;
            }
        }
        return null;
    }

    public function boot(): void
    {
        $this->bindRealizations();

        $this->router = $this->app->make(RouterInterface::class);

        $this->router->scan();
    }

    public function bindRealizations()
    {
        $realization = $this->config->get('app.singleton-realization');

        if (is_array($realization)){
            $this->app->bindMany($realization, true);
        }
    }
}