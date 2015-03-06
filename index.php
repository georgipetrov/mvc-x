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
$registry->set('lib', new lib);
$app->initialize();
$request = new Request();
$registry->set('request', $request);
$session = new Session();
$registry->set('session', $session);
$load = new Load($app,$session,$request, $registry);
$registry->set('load', $load);
$app->load = $load;
$router = new Router($registry);
$registry->set('router', $registry);
$app->router = $router;
$app->router->loader();
