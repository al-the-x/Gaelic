<?php

require_once '../lib/Gaelic/App.php';

define_default( 'GAELIC_APP_PATH', realpath(dirname(__DIR__) . '/app') );

\Gaelic\App::init(array(
    'routes' => array(
        array('/', function( $request ){
            return 'Hello World!';
        }),
        array('/hello/(name)', '\HelloPage', array(
            'name' => 'world',
        )),
    ), // END routes
))->run();

