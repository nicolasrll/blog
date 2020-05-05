<?php

namespace App\Controller;

use Core\DefaultControllerAbstract;
use Core\Request;
use Exception;
use App\Repository\UserManager;

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

            $result = (new UserManager())->findOne(['login' => $formValues['login']]);

            if(empty($result)){
                return $this->renderView(
                    'authentification-admin.html.twig',
                    [
                        'message' => 'Echec de connexion. Login invalide '
                    ]
                );
            }

            if (!password_verify($formValues['password'], $result->getPassword())) {
                return $this->renderView(
                    'authentification-admin.html.twig',
                    [
                        'message' => 'Erreur dans la saisie du mot de passe'
                    ]
                );
            }

            $_SESSION['login'] = $result->getLogin();

            header('Location: /admin/home/');
            exit;
        }

        return $this->renderView(
            'authentification-admin.html.twig'
        );
    }

    public function logoutAction() {
        $_SESSION = array();
        header('Location: /authentification/login');
        exit;
    }
}
