<?php

namespace App\Controller;

use Core\DefaultControllerAbstract;
use Core\Request;
use Exception;
use App\Repository\UserManager;
use App\Entity\User;
use invalidArgumentException;

class AuthentificationController extends DefaultControllerAbstract
{
    public function indexAction(): void
    {
        if ($this->isSubmited('authentification')) {
            $formValues = $this->getFormValues('toto');


            // If we looking another form name than authentification
            if (empty($formValues)) {
                throw new Exception('Le formulaires récupéré est incorrect.');
            }

            $login = $formValues['login'] ?? null;
            $password = $formValues['password'] ?? null;

            if (
                $this->formIsValid($formValues)
                && $this->accountIsExisting($login, $password)
            ) {
                $this->addUserInSession((new UserManager())->findOne(['login' => $formValues['login']])->getLogin());
                $this->redirectTo('/admin');
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

    private function accountIsExisting(?string $login, ?string $password): bool
    {
        if (empty($login) || empty($password)) {
            return false;
        }

        $user = (new UserManager())->findOne(['login' => $login]);
        return null !== $user && $this->checkPassword($password, $user->getPassword());
    }

    private function addUserInSession($userLogin)
    {
        $_SESSION['isLogged'] = true;
        $_SESSION['login'] = $userLogin;
    }

    private function redirectTo($destination) {
        header('Location: ' . $destination);
        exit;
    }
}
