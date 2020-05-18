<?php

namespace App\Controller;

use Core\DefaultControllerAbstract;
use Core\Request;
use Exception;
use App\Repository\UserManager;
use App\Entity\User;

class HomeController extends DefaultControllerAbstract
{
    public function indexAction()
    {
        return $this->renderView(
            'home.html.twig',
            [
                'titlePage' => 'Espace administrateur'
            ]
        );
    }
}
