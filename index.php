<?php
/**
 * An Example
 */

//  Include router
require __DIR__.'/Router.php';

// set Router init
$router = new Rachrouter();
$router->defaultRoute('This is root');

// Map/Register all request
$router->get('say/hello/friends', 'Callback::make');
$router->get('say/hello/{:num}/{:alpha}', 'Include::file');
$router->mix('say/hello/{:alpha}', 'Another::way', ['GET', 'POST', 'PUT', 'OPTIONS']);

// Run Router
$router->run();