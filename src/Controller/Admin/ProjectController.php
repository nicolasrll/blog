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
        $this->renderView('back/project_new.html.twig');
        return $this;
    }

    public function editAction(): void
    {
        $project = $this->checkProjectExistence();
        $projectId = $project->getId();

        if ($this->isSubmited('project'))
        {
            $formValues = $this->getFormValues('project');

            if($this->tokenCSRFIsValidated('tokenEditProject' ,'back/project_edit.html.twig', $formValues, $project->getId())) {
                $this->editProject($project, $formValues);
            }

            return;
        }

        // Sinon on renvoi vers la vue projectform
        $this->generateTokenCSRF();
        $this->renderView(
            'back/project_edit.html.twig',
            [
                'project' => $project,
                'projectId' => $projectId
            ]
        );
        return;
    }

    /*
    public function returnProjectForm(
        string $viewName,
        array $formValues = [],
        string $flashbag = '',
        string $classValue = '',
        string $projectId = ''
    ): self {
        var_dump($formValues); die();
        $this->renderView(
            $viewName,
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
    */

    public function checkInsertion(string $result, array $formValues): self
    {
        $viewName = 'back/project_new.html.twig';
        $flashbag = 'Une erreur est survenue. Le projet n\'a pas été crée';
        $classValue = 'text-danger';

        if ($result) {
            $viewName = 'back/projects.html.twig';
            $flashbag = 'Le projet a été créé avec succès.';
            $classValue = 'text-success';
            $projects = (new ProjectManager())->find();
        }

        //$formValues = $this->getFormValues('project');
        $this->renderView(
            $viewName,
            [
                'project' => $formValues,
                'flashbag' => $flashbag,
                'classValue' => $classValue,
                'projects' => $projects
            ]
        );
        return $this;
    }

    public function tokenCSRFIsValidated(string $tokenName, string $viewOfFail, array $formValues, string $projectId = ''): bool
    {
        if (!$this->checkTokenCSRF($formValues[$tokenName])) {
            $flashbag = 'La création du projet a échoué. Les jetons CSRF ne correspondent pas.';
            $classValue = 'text-danger';
            $this->renderView(
                $viewOfFail,
                [
                    'project' => $formValues,
                    'flashbag' => $flashbag,
                    'classValue' => $classValue,
                    'projectId' => $projectId
                ]
            );

            return false;
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

    public function checkProjectExistence(): Project
    {
        $projectId = $this->getParamAsInt('id');

        if (null == $projectId) {
            throw new Exception('Une erreur est survenue.');
        }

        $project = (new ProjectManager())->findOneById($projectId);

        if (!$project) {
            throw new Exception('Le project que vous souhaitez mettre à jour n\'est plus disponible');
        }

        return $project;
    }

    public function editProject($project, $formValues): void
    {
        $entity = $project->hydrate($formValues);
        $projectEdited = (new ProjectManager())->update($entity);

        if (!$projectEdited) {
            $this->renderView(
                'back/project_edit.html.twig',
                [
                    'project' => $formValues,
                    'flashbag' => 'Votre article n\'a pas pu être modifié. Veuillez réessayer.',
                    'classValue' => 'text-danger'
                ]
            );
            return;
        }

        $this->renderView(
            'back/project.html.twig',
            [
                'project' => $project,
                'flashbag' => 'Votre project a été modifié avec succès',
                'classValue' => 'text-success'
            ]
        );
        return;
}
