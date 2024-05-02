<?php
namespace Src;
//session_set_cookie_params(3600);
session_start();

use Src\Router\extends\Router;

//Set the character encoding for HTML output
header('Content-Type: text/html; charset=UTF-8');

// Set the internal character encoding for multibyte string functions
//mb_internal_encoding('UTF-8');

//set default timezone
date_default_timezone_set('Europe/Kiev');

ini_set('max_execution_time', 100000);
/**
 * set constants for paths to the root folder, main, view, public, css, js
 */
require_once 'config/constants/folder_constants.php';

/**
 * set credentials for third-party API services
 */
require_once CONFIG . '/credentials/api_credentials.php';

/**
 * set constants for the file paths of the pages and their 'title'
 */
require_once CONFIG . '/constants/page_constants.php';

/**
 * Set company constants
 */
require_once CONFIG . '/constants/company.php';

/**
 * api constants
 */
require_once CONFIG . '/api/api.php';

/**
 * set database credentials to connect.
 */
require_once CONFIG . '/credentials/db_credentials.php';

$router = new Router();
$router->route();
