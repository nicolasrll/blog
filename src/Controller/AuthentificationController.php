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
            $flashbag = $this->checkValuesSubmited($formValues);

            if (!empty($flashbag)) {
                $this->renderView(
                    'authentification-admin.html.twig',
                    [
                        'flashbag' => $flashbag
                    ]
                );
                return;
            }

            $_SESSION['isLogged'] = true;
            $_SESSION['login'] = (new UserManager())->findOne(['login' => $formValues['login']])->getLogin();
            header('Location: /admin/home');
            exit;
        }

        $this->generateTokenCSRF();
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

    public function checkValuesSubmited(array $formValues): string
    {
        $message = $this->checkTokenCSRF($formValues['tokenLoginAdmin']) ? '' : 'Une erreur est survenue. Veuillez rafraichir la page.';

        if ($this->checkTokenCSRF($formValues['tokenLoginAdmin'])) {
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
