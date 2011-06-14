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
        $this->_route = $route;

        $this->_handler = $handler;

        $this->_params = $params;

        $this->_defaults = $defaults;
    }

    function match ( $uri )
    {
        return (boolean) preg_match("#{$this->_route}#", $uri);
    }


    function run ( Request $request, Response $response )
    {
    }
} // END Route
