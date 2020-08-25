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
        $this->renderView(
            'back/project.html.twig',
            [
                'project' => (new ProjectManager())->findOneById($projectId),
                'projectId' => $projectId
            ]
        );
        return;
    }

    public function newAction(): void
    {
        if ($this->isSubmited('project')) {
            $formValues = array_map('trim', $this->getFormValues('project'));

            if ($this->priorVerification($formValues, 'tokenNewProject', 'back/project_new.html.twig')) {
                $author = (new UserManager())->findOne(['login' => 'admin-p5']);
                $project = (new Project())->hydrate($formValues)->setUserId($author->getId())->setDateUpdated(date('Y-m-d H:i:s'));
                $result = (new ProjectManager())->insert($project);
                $this->renderViewOnActionInDatabase(
                    $result,
                    'Le projet a été créé avec succès.',
                    'Une erreur est survenue. Le projet n\'a pas été crée',
                    'back/project_new.html.twig',
                    $formValues
                );
            }

            return;
        }

        $this->generateTokenCSRF();
        $this->renderView('back/project_new.html.twig');
    }

    public function editAction(): void
    {
        $projectId = $this->getParamAsInt('id');
        $project = $this->checkProjectExistence($projectId);

        if (!$this->isSubmited('project')) {
            $this->generateTokenCSRF()->renderView(
                'back/project_edit.html.twig',
                [
                    'project' => $project,
                    'projectId' => $projectId
                ]
            );
            return;
        }

        if (isset($project)) {
            $formValues = array_map('trim', $this->getFormValues('project'));

            if ($this->priorVerification($formValues, 'tokenEditProject', 'back/project_edit.html.twig')) {
                $entity = $project->hydrate($formValues);
                $projectEdited = (new ProjectManager())->update($entity);
                $this->renderViewOnActionInDatabase(
                    $projectEdited,
                    'Votre projet a été modifié avec succès',
                    'Votre projet n\'a pas pu être modifié. Veuillez réessayer.',
                    'back/project_edit.html.twig',
                    $formValues
                );
            }
        }
    }

    private function priorVerification(array $formValues, string $tokenName, string $failurePage): bool
    {
        return $this->tokenCSRFIsValidated($tokenName, $failurePage, $formValues,) && $this->checkOnEmptyField($formValues, $failurePage);
    }

    private function tokenCSRFIsValidated(string $tokenName, string $failurePage, array $formValues): bool
    {
        if ($this->checkTokenCSRF($formValues[$tokenName])) {
            return true;
        }

        $this->generateTokenCSRF();
        $this->renderView(
            $failurePage,
            [
                'flashMessage' => 'Les jetons CSRF ne correspondent pas. Veuillez rafraichir la page',
                'project' => $formValues,
                'classValue' => 'text-danger',
                'projectId' => $this->getParamAsInt('id')
            ]
        );
        return false;
    }

    private function checkOnEmptyField(array $formValues, string $failurePage): bool
    {
        if (count(array_filter($formValues)) === count($formValues)) {
            return true;
        }

        $this->generateTokenCSRF();
        $this->renderView(
            $failurePage,
            [
                'flashMessage' => 'Veuillez remplir tous les champs.',
                'project' => $formValues,
                'classValue' => 'text-danger',
                'projectId' => $this->getParamAsInt('id')
            ]
        );
        return false;
    }

    public function checkProjectExistence(int $projectId): ?Project
    {
        if (null !== $projectId) {
            $project = (new ProjectManager())->findOneById($projectId);

            if ($project) {
                return $project;
            }
        }

        $this->renderView(
            'back/projects.html.twig',
            [
                'projects' => (new ProjectManager())->find(),
                'flashMessage' => 'Une erreur est survenue',
                'classValue' => 'text-danger',
            ]
        );
        return null;
    }

    private function renderViewOnActionInDatabase(
        bool $result,
        string $successMessage,
        string $failureMessage,
        string $failureView,
        array $formValues = []
    ): self {
        $flashMessage = $failureMessage;
        $view = $failureView;
        $classValue = 'text-danger';

        if ($result) {
            $flashMessage = $successMessage;
            $view = 'back/projects.html.twig';
            $classValue = 'text-success';
        }

        if(!$result) {
            $this->generateTokenCSRF();
        }

        $this->renderView(
            $view,
            [
                'flashMessage' => $flashMessage,
                'classValue' => $classValue,
                'projectId' => $this->getParamAsInt('id'),
                'project' => $formValues,
                'projects' => (new ProjectManager())->find()
            ]
        );
        return $this;
>>>>>>> refactor #5 (project views, projectController):
    }
}
