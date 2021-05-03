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
$router->get("/createUser", "Web:createUser", "web.createUser");

$router->post("/checkAccount", "Web:checkAccount", "web.checkAccount");
$router->post("/checkCnpj", "Web:checkCnpj", "web.checkCnpj");
$router->post("/checkZone", "Web:checkZone", "web.checkZone");

$router->post("/pswRecovery", "Web:pswRecovery", "web.pswRecovery");

$router->get("/login", "Web:login", "web.login");
$router->post("/validateLogin", "Web:validateLogin", "web.validateLogin");

$router->get("/logout", "Web:logout", "web.logout");

$router->get("/zone/{id}", "Web:zone", "web.zone");
$router->get("/createZone", "Web:createZone", "web.createZone");
$router->post("/validateZone", "Web:validateZone", "web.validateZone");
$router->get("/editFixedZones/{id}", "Web:editFixedZones", "web.editFixedZones");
$router->post("/zoneFixedData", "Web:zoneFixedData", "web.zoneFixedData");
$router->post("/validateEditFixedZone", "Web:validateEditFixedZone", "web.validateEditFixedZone");
$router->post("/newFixedArea", "Web:newFixedArea", "web.newFixedArea");

$router->get("/createAgent", "Web:createAgent", "web.createAgent");
$router->post("/validateAgent", "Web:validateAgent", "web.validateAgent");

$router->get("/profile", "Web:profile", "web.profile");
$router->get("/profileUser/{id}", "Web:profileUser", "web.profileUser");
$router->post("/editProfile", "Web:editProfile", "web.editProfile");
$router->get("/salesmanList", "Web:salesmanList", "web.salesmanList");
$router->get("/salesmanMap", "Web:salesmanMap", "web.salesmanMap");
$router->post("/createNotification", "Web:createNotification", "web.createNotification");
$router->post("/removeSuspension", "Web:removeSuspension", "web.removeSuspension");
$router->post("/validateAuxiliary", "Web:validateAuxiliary", "web.validateAuxiliary");

$router->get("/createSalesman", "Web:createSalesman", "web.createSalesman");
$router->post("/zoneConfirm", "Web:zoneConfirm", "web.zoneConfirm");

$router->get("/agentList", "Web:agentList", "web.agentList");
$router->get("/changeAgentStatus/{agentId}", "Web:changeAgentStatus", "web.changeAgentStatus");

$router->get("/userList", "Web:userList", "web.userList");
$router->get("/changeUserStatus/{userId}", "Web:changeUserStatus", "web.changeUserStatus");

$router->get("/videos", "Web:videos", "web.videos");

$router->get("/neighborhoodList", "Web:neighborhoodList", "web.neighborhoodList");
$router->get("/paymentList", "Web:paymentList", "web.paymentList");
$router->get("/exportData/{fileType}", "Web:exportData", "web.exportData");

$router->post("/securePayment", "Web:securePayment", "web.securePayment");
$router->post("/createPayment", "Web:createPayment", "web.createPayment");

$router->post("/updateUserImg", "Web:updateUserImg", "web.updateUserImg");
$router->get("/downloadFile/{groupName}/{userId}/{fileName}", "Web:downloadFile", "web.downloadFile");

$router->get("/requestLicense", "Web:requestLicense", "web.requestLicense");
$router->get("/requestLicenseUser/{id}", "Web:requestLicenseUser", "web.requestLicenseUser");
$router->get("/licenseList", "Web:licenseList", "web.licenseList");
$router->get("/licenseInfo/{licenseType}/{licenseId}", "Web:licenseInfo", "web.licenseInfo");
$router->get("/order/{type}/{licenseId}", "Web:order", "web.order");
$router->post("/licenseStatus","Web:licenseStatus","web.licenseStatus");
$router->post("/licenseBlock","Web:licenseBlock","web.licenseBlock");

$router->get("/licenseAssociate/{url}", "Web:licenseAssociate", "web.licenseAssociate");
$router->post("/validateLicenseAssociate", "Web:validateLicenseAssociate", "web.validateLicenseAssociate");

$router->get("/salesmanLicense", "Web:salesmanLicense", "web.salesmanLicense");
$router->get("/salesmanLicenseUser/{id}", "Web:salesmanLicenseUser", "web.salesmanLicenseUser");
$router->post("/validateSalesmanLicense", "Web:validateSalesmanLicense", "web.validateSalesmanLicense");

$router->get("/companyLicense", "Web:companyLicense", "web.companyLicense");
$router->post("/validateCompanyLicense", "Web:validateCompanyLicense", "web.validateCompanyLicense");
$router->get("/companyLicenseUser/{id}", "Web:companyLicenseUser", "web.companyLicenseUser");
$router->post("/validateCompanyLicenseUser", "Web:validateCompanyLicenseUser", "web.validateCompanyLicenseUser");

$router->get("/marketLicense", "Web:marketLicense", "web.marketLicense");
$router->get("/marketLicenseUser/{id}", "Web:marketLicenseUser", "web.marketLicenseUser");
$router->post("/marketData", "Web:marketData", "web.marketData");
$router->post("/validateMarketLicense", "Web:validateMarketLicense", "web.validateMarketLicense");

$router->get("/consulta", "WebServiceSIAT:consulta", "WebServiceSIAT.consulta");

$router->get("/response", "WebServiceSIAT:consultaPessoa");
$router->post("/neighborhoodPolygon", "Web:neighborhoodPolygon", "web.neighborhoodPolygon");
$router->get("/neighborhood/{id}", "Web:neighborhood", "web.neighborhood");
$router->post("/findNeighborhood", "Web:findNeighborhood", "web.findNeighborhood");
$router->get("/exportNeighborhood/{neighborhoodId}", "Web:exportNeighborhood", "web.exportNeighborhood");

$router->get("/foodTruckLicense", "Web:foodTruckLicense", "web.foodTruckLicense");
$router->post("/validateFoodTruckLicense", "Web:validateFoodTruckLicense", "web.validateFoodTruckLicense");

$router->get("/occupationLicense", "Web:occupationLicense", "web.occupationLicense");
$router->get("/occupationLicenseUser/{id}", "Web:occupationLicenseUser", "web.occupationLicenseUser");
$router->post("/validateOccupationLicense", "Web:validateOccupationLicense", "web.validateOccupationLicense");

$router->post("/licenseCancel", "Web:licenseCancel", "web.licenseCancel");

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
