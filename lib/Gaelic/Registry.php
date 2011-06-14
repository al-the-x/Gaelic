<?php

namespace Gaelic;

class Registry
{
    protected static $_locked = array();

    protected static $_register = array();


    protected function __construct ( ) { }

    protected function __clone ( ) { }

    static function reset ( )
    {
        static::$_locked = array();

        static::$_register = array();
    }


    static function isLocked ( $key )
    {
        return isset(static::$_locked[$key]);
    }

    static function isRegistered ( $key )
    {
        return isset(static::$_register[$key]);
    }

    static function get ( $key )
    {
        if ( static::isLocked($key) ) return static::$_locked[$key];

        if ( static::isRegistered($key) ) return static::$_register[$key];

        return null;
    }

    static function set ( $key, $value, $locked = false )
    {
        if ( static::isLocked($key) ) throw new Exception\RegistryLockError(
            "'{$key}' is locked and cannot be set() again."
        );

        if ( $locked ) return (static::$_locked[$key] = $value);

        return (static::$_register[$key] = $value);
    }

    static function lock ( $key, $value )
    {
        return static::set($key, $value, true);
    }
} // END Registry
