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

    public function generateTokenCSRF(): self
    {
        // Generates a token for each interaction with the form
        $_SESSION['token'] = bin2hex(random_bytes(32));
        return $this;
    }

    public function tokenCSRFIsValid(string $formTokenValue): bool
    {
        if (
            !empty($formTokenValue)
            && hash_equals($_SESSION['token'], $formTokenValue)
        ) {
            return true;
        }

        $_SESSION['flashMessage'] = 'Les jetons CSRF ne correspondent pas. Veuillez rafraichir la page';

        return false;
    }

    protected function formatTheEntity(AbstractEntity $entity, array $propertiesEntity = []): array
    {
        $entityAsArray = $entity->convertToArray();
        foreach ($propertiesEntity as $value) {
            if(in_array($value, array_keys($entityAsArray))) {
                $entityAsArray[$value] = nl2br(htmlspecialchars($entityAsArray[$value]));
            }
        }

        return $entityAsArray;
    }

    public function formFieldsIsNotEmpty(array $formValues): bool
    {
        if (count(array_filter($formValues)) === count($formValues)) {
            return true;
        }

        $_SESSION['flashMessage'] = 'Veuillez remplir tous les champs.';

        return false;
    }
}

