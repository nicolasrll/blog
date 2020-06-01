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
            $errorMessage = $this->checkTokenCSRF($formValues['adminLoginToken']) ? NULL : 'Une erreur est survenue. Veuillez rafraichir la page.';

            if ($this->checkTokenCSRF($formValues['adminLoginToken'])) {
                $login = $formValues['login'] ? (new UserManager())->findOne(['login' => $formValues['login']]) : null;
                $password = $formValues['password'] ?? null;

                if ($this->accountIsExisting($login, $password)) {
                    $this->addUserInSession($login);
                    $this->redirectTo('/admin');
                }

                $errorMessage = 'Echec dans la tentative de connexion. Veuillez réeessayer';
            }
        }

        $this->generateTokenCSRF();
        $this->renderView(
            'authentification-admin.html.twig',
            [
                'flashMessage' => $errorMessage ?? ''
            ]
        );
    }

    private function checkPassword(string $passwordForm, string $password): bool
    {
        return password_verify($passwordForm, $password);
    }

    public function logoutAction(): void
    {
        session_destroy();

        header('Location: /authentification');
        exit;
    }

    private function accountIsExisting(?User $login, ?string $password): bool
    {
        if (empty($login) || empty($password)) {
            return false;
        }

        return $this->checkPassword($password, $login->getPassword());
        //$user = (new UserManager())->findOne(['login' => $login]);
        //return null !== $user && $this->checkPassword($password, $user->getPassword());
    }

    private function addUserInSession($userLogin): void
    {
        $_SESSION['isLogged'] = true;
        $_SESSION['login'] = $userLogin->getLogin();
    }

    private function redirectTo($destination): void
    {
        header('Location: ' . $destination);
        exit;
    }

    public function checkValuesSubmited($formValues)
    {
        $message = $this->checkTokenCSRF($formValues['adminLoginToken']) ? '' : 'Une erreur est survenue. Veuillez rafraichir la page.';

        if ($this->checkTokenCSRF($formValues['adminLoginToken'])) {
            $user = (new UserManager())->findOne(['login' => $formValues['login']]);

            if (
                empty($user)
                || !$this->checkPassword($formValues['password'], $user->getPassword())
            ) {
                $message = 'Echec dans la tentative de connexion. Veuillez réeessayer';
            }
        }

        return $message;
    }
}
