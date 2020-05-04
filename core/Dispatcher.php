<?php

namespace Core;

use Exception;

/**
 * Used to execute the action in the asssociated controller
 * Initialize property router and controllerPath
 *
 * Exemple:
 *     monsite.fr/article/voir
 *     new Router() = {
 *         controllerPath : 'Controllers/ArticleController.php'
 *         controller : object(ArticleController)
 *     }
 *
 *     monsite.fr/article
 *     new Router() = {
 *         controllerPath : 'Controllers/ArticleController.php'
 *         controller : object(ArticleController)
 *     }
 *
 *     monsite.fr/
 *     new Router() = {
 *         controllerPath : 'Controllers/HomeController.php'
 *         controller : object(HomeController)
 *     }
 */

class Dispatcher
{
    private $router = null;
    private $controllerPath = '';
    private $controller = null;

    public function __construct()
    {
    	$this->router = new Router();
        $basePath = $this->router->isAdmin()
            ? 'src/Controller/Admin/'
            : 'src/Controller/';
        $this->controllerPath = $basePath . ucfirst($this->router->getControllerName()) . '.php';

        return $this;
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

    public function setRouter(Router $router)
    {
        $this->router = $router;
    }

    public function getControllerPath(): string
    {
        return $this->controllerPath;
    }

    public function getController(): DefaultControllerAbstract
    {
        return $this->controller;
    }

    public function setController(string $controllerName)
    {
        $controller =$this->router->isAdmin() ? 'App\Controller\Admin\\' : 'App\Controller\\';
        $controller = $controller . $controllerName;
        $this->controller = new $controller;
    }

    /**
     * Check if controller file exist, include it and call the function action
     */
    public function dispatch(): void
    {
        if (!file_exists($this->getControllerPath()))
        {
            throw new Exception('Le controller recherché n\'existe pas');
        }

        require_once($this->getControllerPath());

        $this->setController($this->getRouter()->getControllerName());

        if (!method_exists($this->controller, $this->getRouter()->getActionName()))
        {
            throw new Exception('L\'action demandé n\'est pas disponible');
        }

        call_user_func([$this->getController() , $this->router->getActionName()]);
    }
}
