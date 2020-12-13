<?php

namespace Steins\Router\Scanner;

use App\Http\Controller\IndexController;
use Steins\Router\Attribute\RouteGroup;
use Steins\Router\Attribute\Route;
use ReflectionAttribute;
use ReflectionClass;

class Scanner implements ScannerInterface
{

    public function scan(string $dir): array
    {
        $files = $this->scanDir($dir);

        return $this->scanAttributes($files);
    }

    public function scanDir(string $dir): array
    {
        $files = array();

        foreach (scandir($dir) as $file){
            $filename = $dir . '/' . $file;

            if ($this->jumpOver($file)){
                continue;
            }

            if (is_dir($filename)){
                $files = array_merge($files, $this->scanDir($filename));
                continue;
            }

            if (str_ends_with($file, '.php')){
                $files[] = $filename;
            }
        }
        return  $files;
    }

    public function scanAttributes(array $files): array
    {
        $routes = array();
        foreach ($files as $file){
            if(null !== $routeGroup = $this->scanAttribute($file)){
                foreach ($routeGroup->routes() as $route){
                    $route->setPrefix($routeGroup->prefix());
                    if (!empty($routeGroup->middleware())){
                        $route->addMiddlewares($routeGroup->middleware());
                    }
                    $routes[join_path($routeGroup->prefix(), $route->uri())] = $route;
                }
            }
        }
        return $routes;
    }

    public function scanAttribute(string $file): RouteGroup | null
    {
        $file = str_replace('/home/ricardo/Document/Code/www/steins/', '', $file);

        $file = $this->toClassName($file);

        try {
            $ref = new \ReflectionClass($file);
        } catch (\ReflectionException) {
            return null;
        }

        $classAttributes = $ref->getAttributes(RouteGroup::class);

        if (empty($classAttributes)) {
            return null;
        }

        $classAttribute = $classAttributes[0];

        $routeGroupRef = new ReflectionClass($classAttribute->getName());
        /** @var RouteGroup $routeGroup */
        $routeGroup = $routeGroupRef->newInstanceArgs($classAttribute->getArguments());

        foreach ($ref->getMethods() as $method){
            $methodAttributes = $method->getAttributes(Route::class);
            if (empty($methodAttributes)){
                continue;
            }
            $methodAttribute = $methodAttributes[0];

            $routeRef = new ReflectionClass($methodAttribute->getName());
            /** @var Route $route */
            $route = $routeRef->newInstanceArgs($methodAttribute->getArguments());

            $route->setHandle($ref->getName() . '@' . $method->getName());

            $routeGroup->addRoute($route);
        }

        return $routeGroup->isEmpty() ? null : $routeGroup;
    }

    protected function toClassName(string $name):string
    {
        $name = str_replace('.php', '', $name);
        $name = str_replace('//', '\\', $name);
        $name = str_replace('/', '\\', $name);
        return ucfirst($name);
    }

    protected function jumpOver($file): bool
    {
        return $file === '.' || $file === '..';
    }
}