<?php

require_once '../lib/Gaelic/App.php';

define_default( 'ROOT_PATH', realpath(dirname(dirname(__FILE__))) );

\Gaelic\App::init(array(
    'include_path' => array( 'app', 'lib' ),
    'routes' => array(
        array('/', '\HelloWorldPage'),
        array('/HelloWorld', function( $request ){
            return 'Hello World!';
        }),
        array('/Hello/:name', '\HelloNamePage', array(
            ':name' => 'World'
        )),
    ),
))->run();

