<?php

namespace Router\Web;

use Controller\Web\WebController;
use Router\AbstractRouter;

class WebRouter extends AbstractRouter
{
    protected string|WebController $currentController = 'WebController';
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

        $type = ucwords($url[1]) ?? 'Web';

        if (isset($url[2])) {
            if (file_exists(SRC . "/Controller/$type/" . $type.ucwords($url[2]) . 'Controller.php')) {
                //make the first letter of the controller name in uppercase format
                $this->currentController = $type.ucwords($url[2]) . 'Controller';
                unset($url[2]);
            }
        }

        $controllerPath = sprintf("Src\\Controller\\$type\\%s", $this->currentController);
        $this->currentController = new $controllerPath();

        if (isset($url[3])) {
            if (method_exists($this->currentController, $url[3])) {
                $this->currentMethod = $url[3];
                unset($url[3]);
            } else {
                $controllerPath = sprintf("Src\\Controller\\$type\\%s", 'PageController');
                $this->currentController = new $controllerPath();
            }
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }
}