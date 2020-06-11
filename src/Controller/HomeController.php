<?php

namespace App\Controller;

use Core\DefaultControllerAbstract;
use Core\Request;
use Exception;
use App\Repository\UserManager;
use App\Repository\ProjectManager;
use App\Entity\User;

class HomeController extends DefaultControllerAbstract
{
    public function indexAction(): void
    {
        $this->renderView(
            'home.html.twig',
            [
                'projects' => (new ProjectManager())->find()
            ]
        );
    }
}
