<?php

namespace App\Controller;

use Core\DefaultControllerAbstract;
use Core\Request;
use App\Repository\ProjectManager;
use App\Entity\Project;


class HomeController extends DefaultControllerAbstract
{
    public function indexAction(): void
    {
        $projects = (new ProjectManager())->find();
        $i = 1;
        foreach ($projects as $value) {
            $formattedEntity = $this->formatTheEntity($value, ['chapo', 'content']);
            $formattedProject[] = (new Project())->hydrate($formattedEntity)->setId($i);
            $i++;
        }

        $this->renderView(
            'home.html.twig',
            [
                'projects' => $formattedProject
            ]
        );
    }
}
