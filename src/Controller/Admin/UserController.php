<?php

namespace App\Controller\Admin;

use Core\AdminControllerAbstract;
use App\Repository\UserManager;

class UserController extends AdminControllerAbstract
{
    public function indexAction()
    {
        return $this->renderView(
            'back/home.html.twig',
            [
                'login' => $_SESSION['login']
            ]
        );
    }

    public function listAction()
    {
        $users = (new UserManager())->find();

        return $this->renderView(
            'back/users.html.twig',
            [
                'users' => $users
            ]
        );
    }
}
