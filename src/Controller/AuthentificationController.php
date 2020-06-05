<?php

namespace App\Controller;

use Core\DefaultControllerAbstract;
use Core\Request;
use Exception;
use App\Repository\UserManager;
use App\Entity\User;

class AuthentificationController extends DefaultControllerAbstract
{
    public function indexAction(): void
    {
        if ($this->isSubmited('authentification')) {
            $formValues = $this->getFormValues('authentification');

            if (!$this->checkValuesSubmited($formValues)) {
                $this->renderView(
                    'authentification-admin.html.twig',
                    [
                        'flashbag' => 'Echec dans la tentative de connexion. Veuillez réeessayer'
                    ]
                );
                return;
            }

            $_SESSION['isLogged'] = true;
            $_SESSION['login'] = (new UserManager())->findOne(['login' => $formValues['login']])->getLogin();
            header('Location: /admin/home');
            exit;
        }

        $this->renderView(
            'authentification-admin.html.twig'
        );
    }

    public function checkPassword(string $passwordForm, string $password): bool
    {
        return password_verify($passwordForm, $password);
    }

    public function logoutAction(): void
    {
        session_destroy();

        header('Location: /authentification');
        exit;
    }

    public function checkValuesSubmited(array $formValues): bool
    {
        $user = (new UserManager())->findOne(['login' => $formValues['login']]);

        if (
            empty($user)
            || !$this->checkPassword($formValues['password'], $user->getPassword())
        ) {
            return false;
        }

        return true;
    }
}
