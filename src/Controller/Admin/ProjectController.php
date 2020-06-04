<?php

namespace App\Controller\Admin;

use Core\AdminControllerAbstract;
use App\Repository\UserManager;
use App\Repository\ProjectManager;
use App\Entity\Project;
use Exception;

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
        $this->renderView(
            'back/project.html.twig',
            [
                'project' => (new ProjectManager())->findOneById($projectId)
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

    private function checkOnEmptyField(array $formValues): bool
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

    public function editAction()
    {
        $projectId = $this->getParamAsInt('id');

        if (null == $projectId) {
            throw new Exception('Une erreur est survenue');
        }

        $projectManager = new ProjectManager();
        $project = $projectManager->findOneById($projectId);

        if (!$project) {
            throw new Exception('Le project que vous souhaitez mettre à jour n\'est plus disponible');
        }

        if ($this->isSubmited('project'))
        {
            $entity = $project->hydrate($this->getFormValues('project'));

            $projectEdited = $projectManager->update($entity);

            return $this->renderView(
                'back/project.html.twig',
                [
                    'project' => $project,
                    'flashbag' => 'Votre project a été modifié avec succès',
                    'classValue' => 'text-success'
                ]
            );
        }

        // Sinon on renvoi vers la vue projectform
        return $this->renderView(
            'back/project_edit.html.twig',
            [
                'project' => $project,
            ]
        );
    }

    public function editAction()
    {
        $projectId = $this->getParamAsInt('id');

        if (null == $projectId) {
            throw new Exception('Une erreur est survenue');
        }

        $projectManager = new ProjectManager();
        $project = $projectManager->findOneById($projectId);

        if (!$project) {
            throw new Exception('Le project que vous souhaitez mettre à jour n\'est plus disponible');
        }

        if ($this->isSubmited('project'))
        {
            $entity = $project->hydrate($this->getFormValues('project'));

            $projectEdited = $projectManager->update($entity);

            return $this->renderView(
                'back/project.html.twig',
                [
                    'project' => $project,
                    'flashbag' => 'Votre project a été modifié avec succès',
                    'classValue' => 'text-success'
                ]
            );
        }

        // Sinon on renvoi vers la vue projectform
        return $this->renderView(
            'back/project_edit.html.twig',
            [
                'project' => $project,
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
