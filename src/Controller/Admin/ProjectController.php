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
        $projectId = $this->getParamAsInt('id');
        $project = (new ProjectManager())->findOneById($projectId);
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
            $formValues = array_map('trim', $this->getFormValues('project'));

            if (
                $this->tokenCSRFIsValidated($formValues)
                && $this->checkOnEmptyField($formValues)
            ) {
                $author = (new UserManager())->findOne(['login' => 'admin-p5']);
                $project = (new Project())->hydrate($formValues)->setUserId($author->getId())->setDateUpdated(date('Y-m-d H:i:s'));
                $result = (new ProjectManager())->insert($project);
                $this->renderViewOnCheckInsertion($result);
            }

            return $this;
        }

        $this->generateTokenCSRF();
        $this->renderNewProjectForm();
        return $this;
    }

    private function renderNewProjectForm(string $flashMessage = '', array $formValues = [], string $classValue = ''): self
    {
        $this->renderView(
            'back/project_new.html.twig',
            [
                'flashMessage' => $flashMessage,
                'project' => $formValues,
                'classValue' => $classValue,
                'author' => 'Nicolas',
                'linkToProject' => 'https://github.com/nicolasrll'
            ]
        );
        return $this;
    }

    private function renderViewOnCheckInsertion(bool $result): self
    {
        if ($result) {
            $this->renderView(
                'back/projects.html.twig',
                [
                    'projects' => (new ProjectManager())->find(),
                    'flashMessage' => 'L\'article a été créé avec succès.',
                    'classValue' => 'text-success'
                ]
            );
            return $this;
        }

        $this->renderNewProjectForm(
            'Une erreur est survenue. L\'article n\'a pas été crée',
            $this->getFormValues('project'),
            'text-danger'
        );
        return $this;
    }

    private function tokenCSRFIsValidated(array $formValues): bool
    {
        if ($this->checkTokenCSRF($formValues['tokenNewProject'])) {
            return true;
        }

        $this->renderNewProjectForm(
            'La création du projet a échoué. Les jetons CSRF ne correspondent pas.',
            $formValues,
            'text-danger'
        );
        return false;
    }

    private function checkOnEmptyField($formValues): bool
    {
        if (count(array_filter($formValues)) === count($formValues)) {
            return true;
        }

        $this->renderNewProjectForm(
            'Veuillez remplir tous les champs.',
            $formValues,
            'text-danger'
        );
        return false;
    }
}
