<?php

session_start();

require 'vendor/autoload.php';
require 'config/conf.php';

use Core\Dispatcher;
use Core\AbstractManager;

error_reporting(E_ALL);
ini_set('display_errors',1);
ini_set('error_reporting', E_ALL);
ini_set('display_startup_errors',1);
error_reporting(-1);


try {
    define('PROJECT_ROOT_PATH', dirname(__FILE__));

    $dispatcher = new Dispatcher();
    $dispatcher->dispatch();
    unset($_SESSION['flashMessage']);
} catch (Exception $e) {
    echo 'Exception reçue : ' . $e->getMessage();
}
