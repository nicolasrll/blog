<?php

namespace App\Controller\Admin;

use Core\AdminControllerAbstract;
use App\Repository\UserManager;
use App\Repository\ProjectManager;
use App\Entity\Project;

class ProjectController extends AdminControllerAbstract
{
    public function indexAction(): void
    {
        $this->renderView(
            'back/projects.html.twig',
            [
                'projects' => (new ProjectManager())->find()
            ]
        );
    }

    public function seeAction(): self
    {
        $projectManager = new ProjectManager();
        $projectId = $this->getParamAsInt('id');
        $project = $projectManager->findOneById($projectId);
        $this->renderView(
            'back/project.html.twig',
            [
                'project' => $project,
            ]
        );

        return $this;
    }

    public function newAction(): self
    {
        if ($this->isSubmited('project')) {
            $formValues = $this->getFormValues('project');

            if ($this->tokenCSRFValidated($formValues)) {
                $author = (new UserManager())->findOne(['login' => 'admin-p5']);
                $project = (new Project())->hydrate($formValues)->setUserId($author->getId())->setDateUpdated(date('Y-m-d H:i:s'));
                $result = (new ProjectManager())->insert($project);
                $this->checkInsertion($result);
            }

            return $this;
        }

        $this->hasCSRFToken();
        $this->renderView(
            'back/project_new.html.twig'
        );

        return $this;
    }

    public function checkInsertion($result): self
    {
        $classValue = 'text-danger';
        $flashbag = 'Une erreur est survenue. L\'article n\'a pas été crée';

        if ($result) {
            $classValue = 'text-success';
            $flashbag = 'L\'article a été créé avec succès.';
        }

        $projects = (new ProjectManager())->find();
        $this->renderView(
            'back/projects.html.twig',
            [
                'projects' => $projects,
                'flashbag' => $flashbag,
                'classValue' => $classValue
            ]
        );

        return $this;
    }

    public function tokenCSRFValidated($formValues): bool
    {
        if (!$this->csrfTokenCheck($formValues['newProjectToken'])) {
            $this->renderView(
                'back/project_new.html.twig',
                [
                    'flashbag' => 'La création du projet a échoué. Les jetons CSRF ne correspondent pas.',
                    'classValue' => 'text-danger',
                    'author' => 'Nicolas',
                    'linkToProject' => 'https://github.com/nicolasrll',
                    'project' => $formValues
                ]
            );

            return false;
        }

        return true;
    }
}
