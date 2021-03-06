<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");
header("Accept: application/json");

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
$router->post("/login", "AuthenticationResource:login", "authenticationResource.login");
$router->get("/logout", "AuthenticationResource:logout", "authenticationResource.logout");
$router->post("/create-account", "AuthenticationResource:createAccount", "authenticationResource.createAccount");

/*
 * Tasks routes
 */
$router->group("task");
$router->get("/", "TaskResource:listAll", "taskResource.listAll");
$router->post("/", "TaskResource:create", "taskResource.create");
$router->delete("/{taskId}", "TaskResource:delete", "taskResource.delete");
$router->delete("/delete-all", "TaskResource:deleteAll", "taskResource.deleteAll");
$router->post("/{taskId}", "TaskResource:update", "taskResource.update");
$router->get("/{taskId}", "TaskResource:listById", "taskResource.listById");
$router->post("/add-task-category", "TaskResource:addTaskCategory", "taskResource.addTaskCategory");
$router->get("/total-registered-tasks", "TaskResource:totalRegisteredTasks", "taskResource.totalRegisteredTasks");
$router->get("/total-pending-tasks", "TaskResource:totalPendingTasks", "taskResource.totalPendingTasks");
$router->get("/total-overdue-tasks", "TaskResource:totalOverdueTasks", "taskResource.totalOverdueTasks");
$router->get("/total-punctual-tasks", "TaskResource:totalPunctualTasks", "taskResource.totalPunctualTasks");
$router->get("/total-tasks-by-categories", "TaskResource:totalTasksByCategories", "taskResource.totalTasksByCategories");
$router->get("/total-done-tasks", "TaskResource:totalDoneTasks", "taskResource.totalDoneTasks");
$router->get("/tasks-by-category/{categoryId}", "TaskResource:tasksByCategory", "taskResource.tasksByCategory");
$router->get("/tasks-by-time-status-category/{timeId}/{statusId}/{categoryId}", "TaskResource:tasksByTime", "taskResource.tasksByTime");
$router->get("/standard-time-task", "TaskResource:standardTimeTask", "taskResource.standardTimeTask");
$router->get("/standard-week-time-task", "TaskResource:standardWeekTimeTask", "taskResource.standardWeekTimeTask");
$router->get("/standard-month-time-task", "TaskResource:standardMonthTimeTask", "taskResource.standardMonthTimeTask");
$router->get("/export-csv/{bearerToken}", "FileResource:exportCsv", "fileResource.exportCsv");

/*
 * Category routes
 */
$router->group("category");
$router->get("/", "CategoryResource:listAll", "categoryResource.listAll");
$router->post("/", "CategoryResource:create", "categoryResource.create");
$router->delete("/{categoryId}", "CategoryResource:delete", "categoryResource.delete");
$router->delete("/delete-all", "CategoryResource:deleteAll", "categoryResource.deleteAll");
$router->post("/{categoryId}", "CategoryResource:update", "categoryResource.update");
$router->get("/{categoryId}", "CategoryResource:listById", "categoryResource.listById");

/*
 * Status routes
 */
$router->group("status");
$router->get("/", "StatusResource:listAll", "statusResource.listAll");

/*
 * Times routes
 */
$router->group("times");
$router->get("/", "TimeResource:listAll", "timeResource.listAll");

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
  echo json_encode(["error" => $router->error()]);
  http_response_code($router->error());
}
