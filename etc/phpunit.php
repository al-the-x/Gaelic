<?php

define('LIB_PATH', realpath(dirname(__DIR__) . '/lib'));

set_include_path(LIB_PATH . PATH_SEPARATOR . get_include_path());

spl_autoload_register(function( $classname ){
    $filename = ltrim(strtr($classname, '\\', '/'), '/') . '.php';

    if ( file_exists(LIB_PATH . '/' . $filename) ) include $filename;
});

