<?php

namespace Core;

use Exception;
use Core\DefaultControllerAbstract;
use App\Entity\User;
use App\Repository\UserManager;

abstract class AdminControllerAbstract extends DefaultControllerAbstract
{
    public function __construct()
    {
        $userRole = (new UserManager())
            ->findOne(['login' => $_SESSION['login']])
            ->getRole();

        if(!$this->roleCheck($userRole)) {
            throw new Exception('Accès refusé.');
        }

        if (false === $this->isLogged()) {
            header('Location: /authentification/login');
            exit;
        }
    }

    public function isLogged(): bool
    {
        return isset($_SESSION['isLogged'])
            && true === $_SESSION['isLogged'];
    }

    public function roleCheck(string $role): bool
    {
        if ($role !== 'admin') {
            return false;
        }

        return true;
    }
}
