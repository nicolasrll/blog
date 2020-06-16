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
        return $this->getFormValues($arg) == [null] ? false : true;
    }
}

