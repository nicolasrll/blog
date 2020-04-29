<?php

namespace App\Controller;

session_start();

require_once('src/Repository/AdminManager.php');

use Core\DefaultControllerAbstract;
use Core\Request;
use Exception;
use App\Repository\AdminManager;

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

            $result = (new AdminManager())->findOne(['login' => $identifiers['login']]);

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
        header('Location: /admin/home/');
    }
}
