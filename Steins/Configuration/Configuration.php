<?php

namespace Steins\Configuration;

use Steins\Application\Application;
use Steins\Singleton\Singleton;

class Configuration implements ConfigInstance
{
    protected array $config = array();

    public function __construct(Application $application)
    {
        $this->load($application->path() . '/config/');
    }

    public function get(string $key = null, $default = null): mixed
    {
        $config = $this->config;
        $keys = explode('.', $key);
        foreach ($keys as $key){
            if (array_key_exists($key, $config)){
                $config = $config[$key];
            }else{
                return $default;
            }
        }
        return $config;
    }

    public function set($key, $value): void
    {
        // TODO: Implement set() method.
    }

    protected function load($path){
        $dir = opendir($path);

        while (false !== ($file = readdir($dir))){
            if (str_ends_with($file, '.php')){
                $this->config[str_replace('.php', '', $file)] = require_once ($path . $file);
            }
        }
    }
}