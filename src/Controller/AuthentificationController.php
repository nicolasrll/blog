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
            $login = empty($formValues['login']) ? null : ($formValues['login']);
            $password = empty($formValues['password']) ? null : $formValues['password'];

            if (
                null !== $login
                && null !== $password
                && $this->accountIsExisting($login, $password)
            ) {
                $_SESSION['isLogged'] = true;
                $_SESSION['login'] = (new UserManager())->findOne(['login' => $formValues['login']])->getLogin();
                header('Location: /admin');
                exit;
            }

            $errorMessage = 'Echec dans la tentative de connexion. Veuillez réeessayer';
        }

        $this->renderView(
            'authentification-admin.html.twig',
            [
                'flashMessage' => $errorMessage ?? ''
            ]
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

    public function accountIsExisting(string $login, string $password): bool
    {
        $user = (new UserManager())->findOne(['login' => $login]);

        return null !== $user && $this->checkPassword($password, $user->getPassword());
    }
}
