<?php

error_reporting(E_ALL);

/*** CONSTANTS ***/
define ('DS', DIRECTORY_SEPARATOR);
define ('SITE_PATH', realpath(dirname(__FILE__)));
define ('SITE_HOST',$_SERVER['HTTP_HOST']);
define ('DIRNAME_X', 'x');

/*** BOOTUP ***/
include '.mvcx/boot.php';

/*** APP LOADS ***/
$app->lib = new lib;
$app->initialize($app->config);
$request = new Request;
$session = new Session;
$app->load = new Load($app,$session,$request);
$app->router = new router($app, $app->load,$request, $session);
$app->router->loader();
