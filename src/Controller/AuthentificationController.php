<?php

namespace App\Controller;

use Core\DefaultControllerAbstract;
use Core\Request;
use Exception;
use App\Repository\UserManager;
use App\Entity\User;

class AuthentificationController extends DefaultControllerAbstract
{
    public function indexAction()
    {
        return $this->renderView(
            'authentification-admin.html.twig',
            [
                'titlePage' => 'Espace administrateur'
            ]
        );
    }

    public function loginAction()
    {
        if ($this->isSubmited('authentification')) {
            $formValues = $this->getFormValues('authentification');
            $user = $this->authentificationChecks($formValues);

            if(null !== $user) {
                $_SESSION['login'] = $user->getLogin();
                header('Location: /admin/home/');
                exit;
            }

            return $this;
        }

        return $this->renderView(
            'authentification-admin.html.twig'
        );
    }

    public function authentificationChecks($formValues): ?User
    {
        $user = (new UserManager())->findOne(['login' => $formValues['login']]);

        if(empty($user)){
            return $this->renderView(
                'authentification-admin.html.twig',
                [
                    'message' => 'Echec de connexion, aucun compte n\'existe pour ce login. Veuillez réessayer. '
                ]
            );
        }

        if (!password_verify($formValues['password'], $user->getPassword())) {
            return $this->renderView(
                'authentification-admin.html.twig',
                [
                    'message' => 'Echec de connexion, le mot de passe est incorrect. Veuillez réessayer.'
                ]
            );
        }

        if($user->getRole() !== 'admin') {
            return $this->renderView(
                'authentification-admin.html.twig',
                [
                    'message' => 'Accès non autorisé. Vous devez être administrateur.'
                ]
            );
        }

        return $user;
    }

    public function logoutAction() {
        session_destroy();
        header('Location: /authentification/login');
        exit;
    }
}
