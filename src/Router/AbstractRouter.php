<?php

namespace Src\Router;

use Src\Controller\AbstractController;

abstract class AbstractRouter
{
    protected string|AbstractController $defaultController = 'WebController';
    protected string $defaultType = 'Web';
    protected string $defaultMethod = 'index';

    protected string|AbstractController $currentController;
    protected string $type;
    protected string $currentMethod;
    protected array $params = [];

    public function redirect($to): void
    {
        header("Location: $to");
    }


    public function route(): void
    {
        /**
         * type      controller_prefix      method
         * /web           /user             /home
         * /api           /admin            /getSomething
         * [ 0               1                 2 ]
         */
        $url = $this->getUrl();
        //var_dump($url);

        /**
         * check the type of request [web, api]
         */
        if(isset($url[0]) && ($url[0] === 'web' || $url[0] === 'api')) {
            $this->type = ucwords($url[0]);
        }

        /**
         * check the specified Controller prefix
         */
        if (isset($url[1]) && $url[1] !== '') {
            $filename = ucwords($url[1]).$this->type . 'Controller.php';
            if (file_exists(SRC . "/Controller/". $this->type."/" . $filename)
            ) {
                //make the first letter of the controller name in uppercase format
                $this->currentController = ucwords($url[1]).$this->type. 'Controller';
                unset($url[1]);
            }
        }

        /**
         * Get object of the Controller class
         */
        $controllerPath = sprintf('Src\Controller\\'.$this->type."\%s", $this->currentController);
        $this->currentController = new $controllerPath();

        /**
         * Check the method within Controller
         */
        if (isset($url[2]) && $url[2] !== '') {
            if (method_exists($this->currentController, $url[2])) {
                $this->currentMethod = $url[2];
                unset($url[2]);
            } else {
                $controllerPath = sprintf("Src\\Controller\\".$this->defaultType."\\%s", $this->defaultController);
                $this->currentController = new $controllerPath();
                $this->currentMethod = $this->defaultMethod;
            }
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    abstract public function getUrl(): array;
}