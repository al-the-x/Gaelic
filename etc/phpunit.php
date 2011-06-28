<?php

set_include_path('./lib' . PATH_SEPARATOR . get_include_path());

require_once 'Gaelic/App.php';

spl_autoload_register(array('\Gaelic\App', 'load'));

