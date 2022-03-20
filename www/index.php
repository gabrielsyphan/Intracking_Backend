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
 * Authentication routes
 */
$router->group("authentication");
$router->post("/login", "AuthenticationResource:login", 'authenticationResource.login');
$router->post("/create-account", "AuthenticationResource:createAccount", 'authenticationResource.createAccount');

/*
 * Tasks routes
 */
$router->group("task");
$router->get("/", "TaskResource:listAll", 'taskResource.listAll');
$router->post("/", "TaskResource:create", 'taskResource.create');
$router->delete("/", "TaskResource:delete", 'taskResource.delete');
$router->update("/", "TaskResource:update", 'taskResource.update');
$router->get("/user-tasks", "TaskResource:listByUser", 'taskResource.listByUser');
$router->get("/{taskId}", "TaskResource:listById", 'taskResource.listById');

/*
 * Error Handler
 */
$router->group("error");
$router->get("/{code}", "AuthenticationResource:errorHandler", "AuthenticationResource.errorHandler");

/**
 * Process
 */
$router->dispatch();

if ($router->error()) {
    $router->redirect("/error/{$router->error()}");
}
