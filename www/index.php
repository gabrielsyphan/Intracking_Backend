<?php

header('Access-Control-Allow-Origin: *');

require __DIR__ . "/vendor/autoload.php";

use CoffeeCode\Router\Router;

$router = new Router(ROOT);

/*
 * Resources
 */
$router->namespace("Source\Resources");

/*
 * Routes
 */
$router->group("api/");
$router->post("/authentication", "AuthenticationResource:login", 'authenticationResource.login');
$router->post("/create-account", "AuthenticationResource:createAccount", 'authenticationResource.createAccount');

/**
 * Process
 */
$router->dispatch();
