<?php

namespace Src\Router\extends;

use Src\Controller\AbstractController;
use Src\Router\AbstractRouter;

class Router extends AbstractRouter
{
    protected string|AbstractController $currentController = 'WebController';
    protected string $type = 'Web';
    protected string $currentMethod = 'index';
    protected array $params = [];
    public function getUrl(): array
    {
        // explode the url-address by slash sign
        /**
         * web/user/recovery?recovery_code=$code"
         *
         * $parts = [web, user, recovery?recovery_code=$code]
         */
        //$urlParts = $this->_splitUrlBySlash();
        $urlParts = explode('/', $_SERVER['REQUEST_URI']);

        /**
         * just remove the first element of the array (name of the website)
         */
        array_shift($urlParts);
        //var_dump($urlParts);
        $size = count($urlParts);

        // get the last item of the given array of url-address exploded by slash
        // to tack if there is GET request or not
        $lastPart = $urlParts[$size - 1];

        /**
         * web/user/recovery?recovery_code=$code"
         *
         * $lastPart = [recovery, recovery_code=$code]
         */
        $lastPartArray = explode('?', $lastPart);

        $lastPartSize = count($lastPartArray);
        // $lastPart = [recovery] -> no GET request
        if ($lastPartSize === 1) {
            return $urlParts;
        } else {
            /**
             * web/user/recovery?recovery_code=$code"
             *
             * $lastPart = [recovery, recovery_code=$code]
             */
            if ($lastPartSize === 2) {
                /**
                 * $urlPart = 'recovery'
                 * $getString = 'recovery_code=$code'
                 */
                $urlPart = $lastPartArray[0];
                $getString = $lastPartArray[1];

                /**
                 * $getStringExplodedByAnd = [recovery_code=$code]
                 */
                $getStringExplodedByAnd = explode('&', $getString);
                $resultGet = [];
                foreach ($getStringExplodedByAnd as $item) {
                    /**
                     * $getStringExplodedByEqualSign = [recovery_code, $code]
                     */
                    $getStringExplodedByEqualSign = explode('=', $item);
                    if (count($getStringExplodedByEqualSign) === 2) {
                        $urlParts[$size - 1] = $urlPart;
                        $resultGet += [
                            /**
                             * recovery_code => $code
                             */
                            $getStringExplodedByEqualSign[0] => $getStringExplodedByEqualSign[1]
                        ];
                    }
                }
                $urlParts += [
                    'get' => $resultGet
                ];
            }
        }
        return $urlParts;
    }
}