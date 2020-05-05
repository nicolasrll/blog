<?php

session_start();

require 'vendor/autoload.php';
require 'config/conf.php';

use Core\Dispatcher;
use Core\AbstractManager;

try {
    define('PROJECT_ROOT_PATH', dirname(__FILE__));

    $dispatcher = new Dispatcher();
    $dispatcher->dispatch();
} catch (Exception $e) {
    echo 'Exception reçue : ' . $e->getMessage();
}
