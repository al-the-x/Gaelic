<?php

namespace Gaelic;

class Request implements \ArrayAccess
{
    const GET = 'GET';

    const POST = 'POST';

    const SESSION = 'SESSION';

    const COOKIE = 'COOKIE';

    protected $_order = array(
        'local', 'GET', 'POST', 'SESSION', 'COOKIE',
    );

    protected $_params;

    function __construct ( )
    {
        $this->_params = array(
            'local'   => array(),
            'GET'     => $_GET,
            'POST'    => $_POST,
            'COOKIE'  => $_COOKIE,
            'SESSION' => ( isset($_SESSION) ?
                $_SESSION : null
            ),
        );
    }


    function offsetExists ( $offset )
    {
        return !is_null($this[$offset]);
    }

    function __isset ( $name )
    {
        return $this->offsetExists($name);
    }

    function offsetGet ( $offset )
    {
        foreach ( $this->_order as $key )
        {
            if ( isset($this->_params[$key][$offset]) )
                return $this->_params[$key][$offset];
        }

        return null;
    }

    function __get ( $name )
    {
        return $this->offsetGet($name);
    }

    function offsetSet ( $offset, $value )
    {
        $this->_params['local'][$offset] = $value;
    }

    function __set ( $name, $value )
    {
        $this->offsetSet($name, $value);
    }

    function offsetUnset ( $offset )
    {
        foreach ( $this->_order as $key )
            if ( isset($this->_params[$key][$offset]) )
            {
                unset($this->_params[$key][$offset]);

                return;
            }
    }

    function __unset ( $name )
    {
        $this->offsetUnset($name);
    }

    function getUri ( )
    {
        static $uri;

        $uri = ( isset($uri) ? $uri : (
            isset($_SERVER['REQUEST_URI']) and !empty($_SERVER['REQUEST_URI']) ?
                $_SERVER['REQUEST_URI'] : '/'
        ));

        return $uri;
    }
} // END Request
