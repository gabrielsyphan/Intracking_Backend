<?php
header('Access-Control-Allow-Origin: *');
session_start();

require __DIR__ . "/vendor/autoload.php";

use Stonks\Router\Router;

$router = new Router(ROOT);

/*
 * Contorllers
 */

$router->namespace("Source\App");

/*
 * Web
 */
$router->group(null);
$router->get("/", "Web:home", "web.home");
$router->post("/formContact", "Web:formContact", "web.formContact");

$router->get("/createAccount", "Web:createAccount", "web.createAccount");
$router->post("/validateAccount", "Web:validateAccount", "web.validateAccount");
$router->get("/confirmAccount/{userId}", "Web:confirmAccount", "web.confirmAccount");
$router->post("/confirmAccountPassword", "Web:confirmAccountPassword", "web.confirmAccountPassword");

$router->post("/checkAccount", "Web:checkAccount", "web.checkAccount");
$router->post("/checkCnpj", "Web:checkCnpj", "web.checkCnpj");

$router->post("/checkZone", "Web:checkZone", "web.checkZone");

$router->post("/pswRecovery", "Web:pswRecovery", "web.pswRecovery");
$router->post("/newPsw", "Web:newPsw", "web.newPsw");

$router->get("/login", "Web:login", "web.login");
$router->post("/validateLogin", "Web:validateLogin", "web.validateLogin");
$router->get("/agent", "Web:agent", "web.agent");
$router->post("/validateAgent", "Web:validateAgent", "web.validateAgent");

$router->get("/logout", "Web:logout", "web.logout");

$router->get("/zone/{id}", "Web:zone", "web.zone");
$router->get("/createZone", "Web:createZone", "web.createZone");
$router->post("/validateZone", "Web:validateZone", "web.validateZone");

$router->get("/createAgent", "Web:createAgent", "web.createAgent");
$router->post("/validateNewAgent", "Web:validateNewAgent", "web.validateNewAgent");

$router->get("/profile", "Web:profile", "web.profile");
$router->get("/salesmanList", "Web:salesmanList", "web.salesmanList");
$router->get("/salesmanMap", "Web:salesmanMap", "web.salesmanMap");
$router->post("/createNotification", "Web:createNotification", "web.createNotification");
$router->post("/removeSuspension", "Web:removeSuspension", "web.removeSuspension");
$router->post("/zoneConfirm", "Web:zoneConfirm", "web.zoneConfirm");

$router->get("/agentList", "Web:agentList", "web.agentList");
$router->get("/changeAgentStatus/{agentId}", "Web:changeAgentStatus", "web.changeAgentStatus");

$router->get("/videos", "Web:videos", "web.videos");

$router->get("/paymentList", "Web:paymentList", "web.paymentList");
$router->get("/exportData/{fileType}", "Web:exportData", "web.exportData");

$router->get("/companyProfile", "Web:companyProfile", "web.companyProfile");

$router->get("/salesman/{id}", "Web:salesmanProfile", "web.salesmanProfile");
$router->get("/company/{id}", "Web:companyInfo", "web.companyInfo");

$router->post("/securePayment", "Web:securePayment", "web.securePayment");
$router->post("/createPayment", "Web:createPayment", "web.createPayment");

$router->post("/updateUserImg", "Web:updateUserImg", "web.updateUserImg");
$router->get("/downloadFile/{groupName}/{userId}/{fileName}", "Web:downloadFile", "web.downloadFile");

/*
 * ERROS
 */
$router->group("ooops");
$router->get("/{errcode}", "Web:error", "web.error");

/**
 * PROCESS
 */
$router->dispatch();

if ($router->error()) {
    $router->redirect("/ooops/{$router->error()}");
}
