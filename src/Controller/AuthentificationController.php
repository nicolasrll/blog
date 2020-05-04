<?php

namespace App\Controller;

//require_once('src/Repository/UserManager.php');

use Core\DefaultControllerAbstract;
use Core\Request;
use Exception;
use App\Repository\UserManager;

class AuthentificationController extends DefaultControllerAbstract
{
    public function indexAction()
    {
        $this->renderView(
            'back/authentification-admin.html.twig',
            [
                'titlePage' => 'Espace administrateur'
            ]
        );
    }

    public function loginAction()
    {
        if ($this->isSubmited('authentification')) {
            $identifiers = ($this->getFormValues('authentification'));

            $result = (new UserManager())->findOne(['login' => $identifiers['login']]);

            if(empty($result)){
                throw new Exception('Echec de connexion. Login invalide ');
            }

            if (!password_verify($identifiers['password'], $result->getPassword())) {
                throw new Exception('Erreur dans la saisie du mot de passe');
            }

            $_SESSION['login'] = $result->getLogin();

            header('Location: /admin/home/');
        }

        return $this->renderView(
            'back/authentification-admin.html.twig'
        );
    }

    public function logoutAction() {
        $_SESSION = array();
        header('Location: /authentification/login');
    }
}
