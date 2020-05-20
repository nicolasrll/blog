<?php

namespace App\Controller\Admin;

use Core\AdminControllerAbstract;

class HomeController extends AdminControllerAbstract
{
    public function indexAction(): AdminControllerAbstract
    {
        return $this->renderView(
            'back/home.html.twig'
        );
    }
}
