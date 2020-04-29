<?php

require 'vendor/autoload.php';
require 'config/conf.php';

use Core\Dispatcher;
use Core\AbstractManager;

//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

try {
    define('PROJECT_ROOT_PATH', dirname(__FILE__));

    $dispatcher = new Dispatcher();
    $dispatcher->dispatch();
} catch (Exception $e) {
    echo 'Exception reçue : ' . $e->getMessage();
} catch (TypeError $e) {
    echo 'Exception: ' . $e->getMessage();
}
