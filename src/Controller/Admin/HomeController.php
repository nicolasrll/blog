<?php

namespace App\Controller\Admin;

use Core\AdminControllerAbstract;

class HomeController extends AdminControllerAbstract
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
}
