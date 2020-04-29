<?php

namespace App\Controller;

session_start();

use Core\DefaultControllerAbstract;

class HomeController extends DefaultControllerAbstract
{
    public function indexAction()
    {
        if ($_SESSION['login']) {
            return $this->renderView(
                'back/home.html.twig',
                [
                    'login' => $_SESSION['login']
                ]
            );
        }

        header('Location: /admin/authentification/login');
    }
}
