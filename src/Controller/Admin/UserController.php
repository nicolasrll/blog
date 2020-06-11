<?php

namespace App\Controller\Admin;

use Core\AdminControllerAbstract;
use App\Repository\UserManager;

class UserController extends AdminControllerAbstract
{
    public function indexAction(): void
    {
        $this->renderView(
            'back/users.html.twig',
            [
                'users' => (new UserManager())->find()
            ]
        );
    }
}
