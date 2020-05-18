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
            $user = (new UserManager())->findOne(['login' => $formValues['login']]);

            if (
                empty($user)
                || !$this->passwordCheck($formValues['password'], $user->getPassword($user->getPassword()))
                || !$this->roleCheck($user->getRole())
            ) {

                return $this->renderView(
                    'authentification-admin.html.twig',
                    [
                        'message' => 'Echec dans la tentative de connexion. Veuillez réeessayer.'
                    ]
                );
            }

            $_SESSION['login'] = $user->getLogin();
            header('Location: /admin/home/');
            exit;
        }

        return $this->renderView(
            'authentification-admin.html.twig'
        );
    }

    public function passwordCheck(string $passwordForm, string $password): bool
    {
        if (!password_verify($passwordForm, $password)) {
            return false;
        }

        return true;
    }

    public function roleCheck(string $role): bool
    {
        if ($role !== 'admin') {
            return false;
        }

        return true;
    }

    public function logoutAction() {
        session_destroy();

        header('Location: /authentification/login');
        exit;
    }
}
