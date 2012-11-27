<?php

session_start();

/* error reporting on */
error_reporting(E_ALL);

/* define the site path */
$site_path = realpath(dirname(__FILE__));
define('SITE_PATH', $site_path);

/* define application constants */
define('TITLE_PREFIX', 'MageshRavi');

/* include the properties file */
include('includes/config.php');

/* include the init.php file */
include 'includes/init.php';

require_once('includes/commonFunctions.inc.php');
require_once('includes/exceptions.inc.php');

/* setting default timezone */
date_default_timezone_set("Asia/Calcutta");

$log = Log::getInstance();

$registry = new Registry;

$registry->db = db::getInstance();

$registry->router = new Router($registry, $log);

$registry->router->setPath(SITE_PATH . '/controller');

$registry->template = new Template($registry);

/* load the controller */
$registry->router->loader();
?>
