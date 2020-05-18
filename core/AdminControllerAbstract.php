<?php

namespace Core;

use Exception;
use Core\DefaultControllerAbstract;

abstract class AdminControllerAbstract extends DefaultControllerAbstract
{
    public function __construct()
    {
        if (false === $this->isLogged()) {
            header('Location: /authentification/login');
            exit;
        }
    }

    public function isLogged(): bool
    {
        return $_SESSION['login'] ?? false;
    }
}
