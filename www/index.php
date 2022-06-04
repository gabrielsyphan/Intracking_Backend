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
$router->delete("/delete-all", "TaskResource:deleteAll", 'taskResource.deleteAll');
$router->post("/{taskId}", "TaskResource:update", 'taskResource.update');
$router->get("/{taskId}", "TaskResource:listById", 'taskResource.listById');
$router->post("/add-task-category", "TaskResource:addTaskCategory", 'taskResource.addTaskCategory');

/*
 * Category routes
 */
$router->group("category");
$router->get("/", "CategoryResource:listAll", 'categoryResource.listAll');
$router->post("/", "CategoryResource:create", 'categoryResource.create');
$router->delete("/", "CategoryResource:delete", 'categoryResource.delete');
$router->delete("/delete-all", "CategoryResource:deleteAll", 'categoryResource.deleteAll');
$router->post("/{categoryId}", "CategoryResource:update", 'categoryResource.update');
$router->get("/{categoryId}", "CategoryResource:listById", 'categoryResource.listById');

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
