<?php

require_once '../lib/Gaelic/App.php';

\Gaelic\App::init(array(
    'ROOT_PATH' => realpath(dirname(dirname(__FILE__))),
    'include_path' => array( 'app', 'lib' ),
    'routes' => array(
        array('/', '\HelloWorldPage'),
    ),
))->run();

