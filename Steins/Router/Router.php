<?php

namespace Steins\Router;

use Exception;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use Steins\Application\Application;
use Steins\Exception\Exceptions\Http\NotFoundExceptions;
use Steins\Router\Attribute\Route;
use Steins\Router\Matcher\MatcherInterface;
use Steins\Router\Scanner\ScannerInterface;

class Router implements RouterInterface
{
    protected array $option;

    protected Application $app;

    protected MatcherInterface $matcher;

    protected ScannerInterface $scanner;

    protected array $routeMap = array();

    public function __construct(Application $app, MatcherInterface $matcher, ScannerInterface $scanner, array $option = [])
    {
        $this->app = $app;
        $this->matcher = $matcher;
        $this->scanner = $scanner;

        $this->setOptions($option);
    }

    public function setOptions(array $option){
        $this->option = [
            'cache' => false,
            'cache_path' => '',
            'scan_dir' => $this->app->path() . '/app/Http/Controller/',
        ];

        foreach ($option as $key => $value){
            $this->option[$key] = $value;
        }
    }

    public function setOption($key, $value){
        $this->option[$key] = $value;
    }

    public function option($key = null)
    {
        if ($key !== null && array_key_exists($key, $this->option)){
            return $this->option[$key];
        }
        return $key;
    }

    public function scan()
    {
        $this->routeMap = $this->scanner->scan($this->option['scan_dir']);
    }

    public function match($uri):array
    {
        $result = array();

        /**
         * @var string $bind
         * @var Route $route
         */
        foreach ($this->routeMap as $bind => $route) {
            $context = $this->matcher->match($bind, $uri);

            if ($context !== false){
                $route->addContexts($context);
                $result[] = $route;
            }
        }

        return $result;
    }

    public function getCache()
    {

    }

    public function cache()
    {

    }
}


//$uri = '/user/1/2/';

//$reg = '/\/user\/(?<id>.*?)\/(.*?)/';

//$reg = '/\/user\/1\/2\//';

//// 转义斜杠
//$reg = str_replace('/', '\/', $uri);
//
//$reg = '/' . $reg . '/';
//
//var_dump($reg);
//
//$result = preg_match_all($reg, $uri, $matches);
//
//var_dump($result);
//var_dump($matches);

//$routeParma = '/user/{user}/info/{id}/';
//
//$uri = '/user/1/info/2/';
//
//$parmaReg = '/{(\w*?)}/';
//
//$result = preg_match_all($parmaReg, $routeParma, $matches);
//
//$parma = array_flip($matches[1]);
//
//var_dump($parma);
//
//$routeReg = $routeParma;
//
//foreach ($matches[0] as $key => $need) {
//    $routeReg = str_replace($need, "(?<{$matches[1][$key]}>.*?)", $routeReg);
//}
//
//// 转义斜杠
//$routeReg = str_replace('/', '\/', $routeReg);
//
//$routeReg = '/' . $routeReg . '/';
//
//$result = preg_match($routeReg, $uri, $matches);
//
//$context = array_intersect_key($matches, $parma);
//
//print_r($matches);
//echo $routeReg . PHP_EOL;
//print_r($context);

//   "/\/use  r\/(?<user>.*?)\/info\/(?<id>.*?)\//"
//   "/\/use\{r\/(?<user>.*?)\/info\/(?<id>.*?)\//"
//   "/\/use {r\/(?<user>.*?)\/info\/(?<id>.*?)\//"
