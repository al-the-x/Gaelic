<?php

namespace Gaelic;

class App
{
    const REGISTRY_KEY = 'application';


    protected $_routes = array();


    protected function __construct ( $options = null )
    {
        if ( is_string($options) ) $options = array(
            'ROOT_PATH' => $options,
        );

        // TODO: Convert $options to Gaelic\Options instance and store...

        $is_option_invalid = (
            !isset($options['ROOT_PATH']) or
            empty($options['ROOT_PATH']) or
            !realpath($options['ROOT_PATH'])
        );

        if ( !defined('ROOT_PATH') and $is_option_invalid )
        {
            require_once 'Exception/ConfigError.php'; // No autoloader yet!

            throw new Exception\ConfigError(
                'The "ROOT_PATH" option should be a full filesystem path that ' .
                'will be prepended to all relative paths.'
            );
        }

        defined('ROOT_PATH') or define('ROOT_PATH', realpath($options['ROOT_PATH']));

        $options['include_path'] = (array) ( isset($options['include_path']) ?
            $options['include_path'] : null
        );

        set_include_path(implode(PATH_SEPARATOR, array_filter(array_map(function($path){
            if ( !ltrim(DIRECTORY_SEPARATOR, $path) === $path )
                $path = ROOT_PATH . "/$path";

            return realpath($path);
        }, $options['include_path']))) .  PATH_SEPARATOR . get_include_path());

        spl_autoload_register(array('\Gaelic\App', 'load'));
    }

    static function init ( $options = null )
    {
        require_once 'Registry.php'; // No autoloader registered yet!

        return Registry::lock(static::REGISTRY_KEY, new static($options));
    }


    static function load ( $classname )
    {
        if ( !$classname ) throw new Exception\LoadError(
            'No classname was provided!'
        );

        // So root-level namespaces don't trip us up...
        $classname = ltrim($classname, '\\');

        require strtr($classname, '\\_', '/') . '.php';
    }


    function addRoute ( Route $route )
    {
        $this->_routes[$route->name] = $route;

        return $this;
    }

    function addRoutes ( array $routes )
    {
        foreach ( $routes as $route ) $this->addRoute($route);
    }

    function dispatch ( Request $request, Response $response )
    {
        $uri = $request->getUri();

        foreach ( $this->_routes as $route )
            if ( $route->match($uri) )
                return $route->run($request, $response);

        return $response;
    }


    function run ( Request $request = null, Response $response = null )
    {
        echo $this->dispatch($request, $response);
    }
} // END App
