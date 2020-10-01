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

    public function seeAction(): void
    {
        $projectId = $this->getParamAsInt('id');
        $projectFind = (new ProjectManager)->findOneById($projectId);
        $formattedEntity = $this->formatTheEntity($projectFind, ['chapo', 'content']);

        $project = (new Project())->hydrate($formattedEntity)->setId($projectId);
        $this->renderView(
            'back/project.html.twig',
            [
                'project' => $project,
                'projectId' => $projectId
            ]
        );
    }

    public function newAction(): void
    {
        $projectInserted = 0;

        if ($this->isSubmited('project')) {
            $formValues = array_map('trim', $this->getFormValues('project'));

            if (
                $this->tokenCSRFIsValid($formValues['tokenNewProject'])
                && $this->formFieldsIsNotEmpty($formValues)
            ) {
                $author = (new UserManager())->findOne(['login' => 'admin-p5']);
                $project = (new Project())->hydrate($formValues)->setUserId($author->getId())->setDateUpdated(date('Y-m-d H:i:s'));
                $projectInserted = (new ProjectManager())->insert($project);
                $_SESSION['flashMessage'] = $projectInserted ? 'Le projet a été créé avec succès.' : 'Une erreur est survenue. Le projet n\'a pas été crée';
            }
        }

        $this->generateTokenCSRF();
        $this->renderView(
            $projectInserted ? 'back/projects.html.twig' : 'back/project_new.html.twig',
            [
                'flashMessage' => $_SESSION['flashMessage'] ?? '',
                'classValue' => $projectInserted ? 'text-success' : 'text-danger',
                'projects' => (new ProjectManager())->find(),
                'project' => $formValues ?? '',
            ]
        );
    }

    public function editAction(): void
    {
        $projectId = $this->getParamAsInt('id');
        $project = $this->checkProjectExistence($projectId);
        $projectEdited = false;

        if (
            isset($project)
            && $this->isSubmited('project')
        ) {
            $formValues = array_map('trim', $this->getFormValues('project'));

            if (
                $this->tokenCSRFIsValid($formValues['tokenEditProject'])
                && $this->formFieldsIsNotEmpty($formValues)
            ) {
                $entity = $project->hydrate($formValues);
                $projectEdited = (new ProjectManager())->update($entity);
                $_SESSION['flashMessage'] = $projectEdited ? 'Votre projet a été modifié avec succès' : 'Votre projet n\'a pas pu être modifié. Veuillez réessayer.';
            }
        }

        $this->generateTokenCSRF()->renderView(
            $projectEdited ? 'back/projects.html.twig' : 'back/project_edit.html.twig',
            [
                'project' => $formValues ?? $project,
                'projectId' => $projectId,
                'flashMessage' => $_SESSION['flashMessage'] ?? '',
                'classValue' => $projectEdited ? 'text-success' : 'text-danger',
                'projects' => (new ProjectManager())->find(),
            ]
        );
    }

    public function deleteAction(): void
    {
        $projectId = $this->getParamAsInt('id');
        $project = $this->checkProjectExistence($projectId);
        $projectDeleted = false;

        if(isset($project)) {
            $projectDeleted = (new ProjectManager())->delete($projectId);
            $_SESSION['flashMessage'] = $projectDeleted ? 'Votre projet a été supprimé avec succès' : 'Votre projet n\'a pas pu être supprimé.';
        }

        $this->renderView(
            'back/projects.html.twig',
            [
                'projects' => (new ProjectManager())->find(),
                'flashMessage' => $_SESSION['flashMessage'] ?? '',
                'classValue' => $projectDeleted ? 'text-success' : 'text-danger'
            ]
        );
    }

    private function checkProjectExistence(int $projectId): ?Project
    {
        if (null !== $projectId) {
            $project = (new ProjectManager())->findOneById($projectId);

            if ($project) {
                return $project;
            }
        }

        $_SESSION['flashMessage'] = 'Une erreur est survenue';

        return null;
    }
}
