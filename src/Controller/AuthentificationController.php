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

        if (empty($user)) {
            return $this->renderView(
                'authentification-admin.html.twig',
                [
                    'message' => 'Echec de connexion, aucun compte n\'existe pour ce login. Veuillez réessayer. '
                ]
            );
        }

        if (
            !$this->passwordCheck($formValues['password'], $user->getPassword($user->getPassword()))
            || !$this->roleCheck($user->getRole())
        ) {
            return null;
        }

        return $user;
    }

    public function passwordCheck($passwordForm, $password)
    {
        if (!password_verify($passwordForm, $password)) {
            return $this->renderView(
                'authentification-admin.html.twig',
                [
                    'message' => 'Echec de connexion, le mot de passe est incorrect. Veuillez réessayer.'
                ]
            );
        }

        return $this;
    }

    public function roleCheck($role)
    {
        if ($role !== 'admin') {
            return $this->renderView(
                'authentification-admin.html.twig',
                [
                    'message' => 'Accès non autorisé. Vous devez être administrateur.'
                ]
            );
        }

        return $this;
    }

    public function logoutAction() {
        session_destroy();

        header('Location: /authentification/login');
        exit;
    }
}
