<?php

namespace Gaelic;

class App
{
    const REGISTRY_KEY = 'application';

    protected function __construct ( $options = null )
    {
        // TODO: Convert $options to Gaelic\Options instance and store...
    }

    static function init ( $options = null )
    {
        spl_autoload_register(array('\Gaelic\App', 'load'));

        define('ROOT_PATH', $options['ROOT_PATH']);

        set_include_path(implode(PATH_SEPARATOR, array_map(function($path){
            return realpath( (strstr($path, DIRECTORY_SEPARATOR) === $path) ?
                $path : ROOT_PATH . '/' . $path
            );
        }, $options['include_path']) + array( get_include_path() )));

        return Registry::lock(static::REGISTRY_KEY, new static($options));
    }


    static function load ( $classname )
    {
        if ( !$classname ) throw new Exception\LoadError(
            'No classname was provided!'
        );

        require strtr($classname, '\\_', '/') . '.php';
    }


    function run ( Request $request = null, Response $response = null )
    {
        // TODO: Dispatch the application...

        echo 'Hello World!', "\n";
    }
} // END App
