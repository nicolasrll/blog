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
        if (false === $this->isLogged() || !isset($_SESSION['login'])) {
            header('Location: /authentification');
            exit;
        }

        $userRole = (new UserManager())
            ->findOne(['login' => $_SESSION['login']])
            ->getRole();

        if (!$this->isAdmin($userRole)) {
            throw new Exception('Accès refusé.');
        }
    }

    public function isLogged(): bool
    {
        return isset($_SESSION['isLogged'])
            && true === $_SESSION['isLogged'];
    }

    public function isAdmin(string $role): bool
    {
        return 'admin' === $role;
    }
}
