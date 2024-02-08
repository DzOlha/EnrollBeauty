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
        /**
         * Get the url after domain name, but without any queries
         */
        $urlAfterDomain = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        /**
         * Explode the url by slash
         */
        $urlExploded = explode('/', $urlAfterDomain);

        /**
         * Remove the first empty element (because the url starts from /)
         */
        unset($urlExploded[0]);

        /**
         * Reindex the array to start from index 0 not 1.
         */
        $urlReindex = array_values($urlExploded);

        return $urlReindex;
    }
}