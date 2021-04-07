<?php
/**
 * An Example
 */

// error_reporting(1);
// ini_set('display_errors', 1);

define('DS', DIRECTORY_SEPARATOR);
// Helper
require __DIR__.DS.'helper.php';
//  Include router
requireFile('Router.php');

// set Router init
$router = new Rachrouter();
$router->defaultRoute('This is root');
$router->setDefaultControllerPath(__DIR__.DS.'Controller'.DS);
// Map/Register all request
$router->get('myname/drajat', 'Class::Test@printa');
$router->get('say/hello/friends', 'Include::PageHal.php');
$router->get('say/hello/{:num}/{:alpha}', 'Include::file');
$router->mix('say/hello/{:alpha}', 'Another::way', ['GET', 'POST', 'PUT', 'OPTIONS']);

// Run Router
$router->run();