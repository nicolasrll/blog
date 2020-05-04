<?php

namespace Core;

//use Twig\Loader\FilesystemLoader;
//use Twig\Environment;
use Exception;
use Core\DefaultControllerAbstract;

abstract class AdminControllerAbstract extends DefaultControllerAbstract
{
    public function __construct()
    {
        if (!isset($_SESSION['login'])) {
            $_SESSION['login'] = '';
        }

        if (false === $this->isLogged()) {
            header('Location: /authentification/login');
        }
    }

    public function isLogged(): bool
    {
        return $_SESSION['login'];
    }
}
