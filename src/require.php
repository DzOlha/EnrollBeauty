<?php
namespace Src;

session_start(); //session_destroy will be in the logout UserController
use Src\Router\extends\Router;
use Src\Router\extends\Web\WebRouter;

//Set the character encoding for HTML output
header('Content-Type: text/html; charset=UTF-8');

// Set the internal character encoding for multibyte string functions
mb_internal_encoding('UTF-8');

//set defaul timezone
date_default_timezone_set('Europe/Kiev');

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
 * api constants
 */
require_once SRC.'/config/api/api.php';

/**
 * set database credentials to connect.
 */
require_once SRC.'/DB/Config/connection.php';

$router = new Router();
$url = $router->getUrl();
$router->route();
