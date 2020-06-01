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
        if ($this->isSubmited('authentification')) {
            $formValues = $this->getFormValues('authentification');
            $flashbag = $this->checkValuesSubmited($formValues);

            if (!empty($flashbag)) {
                return $this->renderView(
                    'authentification-admin.html.twig',
                    [
                        'flashbag' => $flashbag
                    ]
                );
            }

            $user = (new UserManager())->findOne(['login' => $formValues['login']]);
            $_SESSION['login'] = $user->getLogin();

            header('Location: /admin/home');
            exit;
        }

        $this->generateTokenCSRF();

        return $this->renderView(
            'authentification-admin.html.twig'
        );
    }

    public function checkPassword(string $passwordForm, string $password): bool
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

    public function logoutAction(): self
    {
        session_destroy();

        header('Location: /authentification');
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
