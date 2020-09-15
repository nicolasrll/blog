<?php

namespace App\Controller;

use Core\DefaultControllerAbstract;
use App\Repository\ProjectManager;
use App\Entity\Project;

class HomeController extends DefaultControllerAbstract
{
    public function indexAction(): void
    {
        $projects = (new ProjectManager())->find();

        foreach ($projects as $value) {
            $formattedEntity = $this->formatTheEntity($value, ['chapo', 'content']);
            $formattedProject[] = (new Project())->hydrate($formattedEntity)->setId($value->getId());
        }

        $this->renderView(
            'home.html.twig',
            [
                'projects' => $formattedProject
            ]
        );
    }
}
