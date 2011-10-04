<?php

namespace Gaelic;

class Route // TODO: extends Object
{
    protected $_route;

    protected $_handler;

    protected $_params = array();

    protected $_defaults = array();


    function __construct ( $route, $handler, $params = array(), $defaults = array() )
    {
        // TODO: $this->initOptions(compact('route', 'handler', 'params', 'defaults'));

        $this->_route = $route;

        $this->_handler = $handler;

        $this->_params = $params;

        $this->_defaults = $defaults;
    }

    function match ( $uri )
    {
        return (boolean) preg_match("#^{$this->_route}/?$#", $uri);
    }


    function getHandler ( )
    {
        if ( ! $this->_handler ) throw new ConfigError(
            'No "handler" has been provided for this Route: ' . $this->_route
        );

        return $this->_handler;
    }


    function initHandler ( Request $request, Response $response )
    {
        $handler = $this->getHandler();

        if ( is_callable($handler) )
            return call_user_func_array($handler, array($request, $response));

        if ( is_string($handler) and in_array('Gaelic\Handler', class_parents($handler)) )
            return new $handler($request, $response);

        return $handler($request, $response);
    }


    function run ( Request $request, Response $response )
    {
        $handler = $this->initHandler($request, $response);

        return $handler($request->getMethod());
    }

    function __invoke ( Request $request, Response $response )
    {
        return $this->run($request, $response);
    }
} // END Route
