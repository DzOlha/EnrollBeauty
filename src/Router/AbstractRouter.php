<?php

namespace Src\Router;

use Src\Controller\AbstractController;
use Src\Controller\Api\AdminApiController;
use Src\Controller\Web\AdminWebController;
use Src\Helper\Data\AdminDefault;

abstract class AbstractRouter
{
    protected string|AbstractController $defaultController = 'WebController';
    protected string $defaultType = 'Web';
    protected string $defaultMethod = 'index';
    protected string $pageNotFoundMethod = 'error';

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
//        var_dump($url);
        $isHomepage = $url[0] === '';

        /**
         * Check if the url is for initial registration of the default admin
         */
        $registerAdminUrl = AdminDefault::getRegistrationUrl();
        if($registerAdminUrl && $url[0] === $registerAdminUrl) {
            call_user_func_array(
                [new AdminWebController(), 'adminDefaultRegistration'],
                $this->params
            );
        }

        /**
         * check the type of request [web, api]
         */
        if (isset($url[0]) && ($url[0] === 'web' || $url[0] === 'api')) {
            $this->type = ucwords($url[0]);
        } else {
            $this->type = $this->defaultType;
        }

        $setDefaultController = true;
        /**
         * check the specified Controller prefix
         */
        if (isset($url[1]) && $url[1] !== '') {
            $filename = ucwords($url[1]) . $this->type . 'Controller.php';
            if (file_exists(SRC . "/Controller/" . $this->type . "/" . $filename)
            ) {
                //make the first letter of the controller name in uppercase format
                $this->currentController = ucwords($url[1]) . $this->type . 'Controller';
                unset($url[1]);
                $setDefaultController = false;
            }
        }

        /**
         * Get object of the Controller class
         */
        $fileExists = file_exists(
            SRC . "/Controller/" . $this->type . "/" . $this->currentController.".php"
        );
        if ($fileExists) {
            $controllerPath = sprintf(
                'Src\Controller\\' . $this->type . "\%s", $this->currentController
            );
            if ($setDefaultController && !$isHomepage) {
                $this->currentMethod = $this->pageNotFoundMethod;
            }
        }
        $this->currentController = new $controllerPath();

        /**
         * Check the method within Controller
         */
        if (isset($url[2]) && $url[2] !== '') {
            if (method_exists($this->currentController, $url[2])) {
                $this->currentMethod = $url[2];
                unset($url[2]);
            } else {
                $this->currentMethod = $this->pageNotFoundMethod;
            }
        }
        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    abstract public function getUrl(): array;
}