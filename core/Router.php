<?php

namespace Core;

/**
 * Used to retrieve controllerName and actionName in request
 *
 * @author Nicolas Rellier <nicolasrellier@yahoo.fr>
 *
 *
 *
 * initialize $request with object passed in parameter,
 * $controllerName and $actionName with url exploded
 *
 * Exemple:
 *     monsite.fr/article/voir
 *
 *     new Router() = {
 *         controllerName : 'ArticleController'
 *         actionName : 'voirAction'
 *     }
 *
 *     monsite.fr/article
 *     new Router() = {
 *         controllerName : 'ArticleController'
 *         actionName : 'indexAction'
 *     }
 *
 *     monsite.fr/
 *     new Router() = {
 *         controllerName : 'AccueilController'
 *         actionName : 'indexAction'
 *     }
 */

class Router
{
    private $request;
    private $controllerName = 'HomeController';
    private $actionName = 'indexAction';
    private $admin = false;
    private $controllerPosition = 0;

    public function __construct()
    {
        $this->request = Request::getInstance();
        $param = $this->request->getUrlExplodedByIndex(0);

        if (isset($param) && 'admin' === $this->request->getUrlExplodedByIndex(0)) {
            ++$this->controllerPosition;
            $this->admin = true;
        }

        $controllerName = $this->getRequest()->getUrlExplodedByIndex($this->controllerPosition) ?? 'Home';
        $actionName = $this->getRequest()->getUrlExplodedByIndex(++$this->controllerPosition) ?? 'index';

        $this->setControllerName($controllerName)
            ->setActionName($actionName);
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    public function getControllerName(): string
    {
        return $this->controllerName;
    }

    public function setControllerName(string $controllerName)
    {
        if (!empty($controllerName)) {
            $this->controllerName = ucfirst($controllerName).'Controller';
        }

        return $this;
    }

    public function getActionName(): string
    {
        return $this->actionName;
    }

    public function setActionName(string $actionName)
    {
        if (!empty($actionName)) {
            $this->actionName = $actionName . 'Action';
        }

        return $this;
    }

    public function isAdmin(): bool
    {
        return $this->admin;
    }
}
