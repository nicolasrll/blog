<?php

namespace App\Controller\Admin;

use Core\AdminControllerAbstract;
use App\Repository\UserManager;
use App\Repository\ProjectManager;
use App\Entity\Project;
use Exception;

class ProjectController extends AdminControllerAbstract
{
    public function indexAction()
    {
        return $this->renderView(
            'back/projects.html.twig',
            [
                'projects' => (new ProjectManager())->find()
            ]
        );
    }

    public function seeAction()
    {
        $projectManager = new ProjectManager();
        $projectId = $this->getParamAsInt('id');
        $project = $projectManager->findOneById($projectId);

        return $this->renderView(
            'back/project.html.twig',
            [
                'project' => $project,
            ]
        );
    }

    public function newAction()
    {
        if ($this->isSubmited('project')) {
            $formValues = $this->getFormValues('project');
            $classValue = 'text-danger';

            if (!$this->csrfTokenCheck($formValues['token'])) {

                return $this->renderView(
                    'back/project_new.html.twig',
                    [
                        'flashbag' => 'La création du projet a échoué. Les jetons CSRF ne correspondent pas.',
                        'classValue' => $classValue,
                        'author' => 'Nicolas',
                        'linkToProject' => 'https://github.com/nicolasrll'
                    ]
                );
            }

            $project = (new Project())->hydrate($formValues);

            $userManager = new UserManager();
            $author = $userManager->findOne(['login' => 'admin-p5']);
            $project->setUserId($author->getId());
            $project->setDateUpdated(date('Y-m-d H:i:s'));
            $projectManager = new ProjectManager();
            $result = $projectManager->insert($project);

            $projects = $projectManager->find();

            $classValue = 'text-danger';
            $flashbag = 'Une erreur est survenue. L\'article n\'a pas été crée';
            if ($result) {
                $classValue = 'text-success';
                $flashbag = 'L\'article a été créé avec succès.';
            }

            return  $this->renderView(
                'back/projects.html.twig',
                [
                    'projects' => $projects,
                    'flashbag' => $flashbag,
                    'classValue' => $classValue
                ]
            );
        }

        $this->hasCSRFToken();

        return $this->renderView(
            'back/project_new.html.twig',
            [
                'author' => 'Nicolas',
                'linkToProject' => 'https://github.com/nicolasrll',
                'classValue' => 'text-danger'
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
    }
}
