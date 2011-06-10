<?php

namespace Gaelic;

class Route // TODO: extends Object
{
    protected $_options = array();

    function __construct ( $route, $handler, $params = array(), $defaults = array() )
    {
        // TODO: Initialize new \Gaelic\Options from compact() instead...
        // parent::__construct(compact($route, $handler, $params, $defaults))
        $this->option(compact($route, $handler, $params, $defaults));
    }

    // TODO: Move to \Gaelic\Options::get/set/__construct...
    function option ( $name, $value = null )
    {
        if ( is_null($value) )
        {
            if ( is_array($name) )
            {
                foreach ( $name as $key => $value )
                {
                    $this->_options["_{$key}"] = $value;
                }

                return $this;
            }

            return $this->_options["_{$name}"];
        }

        $this->_options["_{$name}"] = $value;

        return this;
    } // END option
} // END Route
