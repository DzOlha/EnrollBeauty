<?php
namespace Src;

//session_start(); //session_destroy will be in the logout UserController
use Src\Router\extends\Router;
use Src\Router\extends\Web\WebRouter;

ini_set('max_execution_time', 100000);
/**
 * set constants for paths to the root folder, main, view, public, css, js
 */
require_once 'config/folder_constants.php';

/**
 * set constants for root urls
 */
require_once SRC.'/config/url_constants.php';

/**
 * set database credentials to connect.
 */
require_once SRC.'/DB/Config/connection.php';

$router = new Router();
$url = $router->getUrl();
$router->route();
