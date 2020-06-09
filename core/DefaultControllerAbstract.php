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

        return is_array($searchValue) ? $searchValue : [$searchValue];
    }

    public function isSubmited(string $arg): bool
    {
        // Check if we passed another thing than an array
        if($this->getFormValues($arg) == [null]) {
            return false;
        }

        return true;
    }

    public function generateTokenCSRF(): void
    {
        // Generates a token for each interaction with the form
        $_SESSION['token'] = bin2hex(random_bytes(32));
    }

    public function checkTokenCSRF($formTokenValue): bool
    {
        if (!empty($formTokenValue) && isset($formTokenValue)) {
            if(hash_equals($_SESSION['token'], $formTokenValue)) {
                return true;
            }
        }

        return false;
    }
}

