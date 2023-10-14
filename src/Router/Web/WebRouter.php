<?php

namespace Src\Router\Web;

use Src\Controller\Web\WebController;
use Src\Router\AbstractRouter;

class WebRouter extends AbstractRouter
{
    protected string|WebController $currentController = 'WebController';
    protected string $type = 'Web';
    protected string $currentMethod = 'index';
    protected array $params = [];

    public function route(): void
    {
        /**
         * web/user/home
         * api/admin/getSomething
         * [0    1      2]
         */
        //
        $url = $this->getUrl();
        //var_dump($url);

        if(isset($url[0]) && $url[0] !== '') {
            $this->type = ucwords($url[0]);
        }

        if (isset($url[1]) && $url[1] !== '') {
            $filename = ucwords($url[1]).$this->type . 'Controller.php';
            if (file_exists(SRC . "/Controller/". $this->type."/" . $filename)
            ) {
                //make the first letter of the controller name in uppercase format
                $this->currentController = ucwords($url[1]).$this->type. 'Controller';
                unset($url[1]);
            }
        }

        //$namespace = "Src\Controller\Web\WebController";
        $controllerPath = sprintf('Src\Controller\\'.$this->type."\%s", $this->currentController);
        //$controllerPath = $namespace;
        $this->currentController = new $controllerPath();

        if (isset($url[2]) && $url[2] !== '') {
            if (method_exists($this->currentController, $url[2])) {
                $this->currentMethod = $url[2];
                unset($url[2]);
            } else {
                $controllerPath = sprintf("Src\\Controller\\".$this->type."\\%s", 'WebController');
                $this->currentController = new $controllerPath();
            }
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }
}