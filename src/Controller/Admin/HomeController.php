<?php

namespace App\Controller\Admin;

use Core\AdminControllerAbstract;

class HomeController extends AdminControllerAbstract
{
    public function indexAction(): void
    {
        $this->renderView(
            'back/home.html.twig'
        );
    }
}
