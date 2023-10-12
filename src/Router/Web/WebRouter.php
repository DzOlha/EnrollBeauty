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
         * enroll.beauty/web/user/home
         * enroll.beauty/api/admin/getSomething
         * [     0        1    2      3]
         */
        //
        $url = $this->getUrl();

        if(isset($url[1]) && $url[1] !== '') {
            $this->type = ucwords($url[1]);
        }

        if (isset($url[2]) && $url[1] !== '') {
            if (file_exists(SRC . "/Controller/".$this->type."/" . $this->type.ucwords($url[2]) . 'Controller.php')) {
                //make the first letter of the controller name in uppercase format
                $this->currentController = $this->type.ucwords($url[2]) . 'Controller';
                unset($url[2]);
            }
        }

        //$namespace = "Src\Controller\Web\WebController";
        $controllerPath = sprintf('Src\Controller\\'.$this->type."\%s", $this->currentController);
        //$controllerPath = $namespace;
        $this->currentController = new $controllerPath();

        if (isset($url[3]) && $url[1] !== '') {
            if (method_exists($this->currentController, $url[3])) {
                $this->currentMethod = $url[3];
                unset($url[3]);
            } else {
                $controllerPath = sprintf("Src\\Controller\\".$this->type."\\%s", 'WebController');
                $this->currentController = new $controllerPath();
            }
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }
}