<?php

namespace Core;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Exception;

abstract class DefaultControllerAbstract
{
    abstract protected function indexAction();

    protected function renderView(string $view, array $params = [])
    {
        require_once 'vendor/autoload.php';

        $loader = new FilesystemLoader('template/');
        $twig = new Environment($loader);
        $twig->addExtension(new \Twig\Extension\DebugExtension());
        // share php session variable
        $twig->addGlobal('session', $_SESSION);

        echo $twig->render($view, $params);
    }

    /**
     * @param  string $searching
     * @return int|null
     */
    public function getParamAsInt(string $searching): ?int
    {
        $searchValue = (Request::getInstance())->getParam($searching);

        return $searchValue ? (int) $searchValue : null;
    }

    /**
     * Called to get values submited in form
     * @param  string $searching
     * @return array
     */
    public function getFormValues(string $searching): array
    {
        $searchValue = (Request::getInstance())->getParam($searching);

        return is_array($searchValue)
            ? $searchValue
            :(
                null !== $searchValue
                    ? [$searching => $searchValue]
                    : []
            );
    }

    public function isSubmited(string $arg): bool
    {
        // Check if we passed another thing than an array
        return $this->getFormValues($arg) !== [];
    }

    /*
    protected function formIsValid(array $formValues): bool
    {
        return is_array($formValues) || !empty($formValues);
    }
    */

    public function generateTokenCSRF(): void
    {
        // Generates a token for each interaction with the form
        $_SESSION['token'] = bin2hex(random_bytes(32));
    }

    public function checkTokenCSRF(string $formTokenValue): bool
    {
        return !empty($formTokenValue) && hash_equals($_SESSION['token'], $formTokenValue);
    }

    public function hasCSRFToken()
    {
        // Generates a token for each interaction with the form
        $_SESSION['token'] = bin2hex(random_bytes(32));
    }

    public function csrfTokenCheck($formTokenValue): bool
    {
        if (!empty($formTokenValue)) {
            if(!hash_equals($_SESSION['token'], $formTokenValue)) {
                //throw new Exception('Un problème a été rencontré. Veuillez recommencer.');
                return false;
            }
        }

        return true;
    }
}

