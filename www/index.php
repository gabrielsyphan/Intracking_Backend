<?php

header('Access-Control-Allow-Origin: *');

require __DIR__ . "/vendor/autoload.php";

use CoffeeCode\Router\Router;

$router = new Router(ROOT);

/*
 * Contorllers
 */
$router->namespace("Source\App");

/*
 * Web
 */
$router->group(null);
$router->get("/", "Web:home", 'web.home');

/**
 * PROCESS
 */
$router->dispatch();

if ($router->error()) {
	$router->redirect("/ooops/{$router->error()}");
}
