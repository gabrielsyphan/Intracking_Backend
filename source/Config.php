<?php

define("ROOT", "https://localhost/orditi");
define("THEMES", __DIR__."/../themes");
define("SERVICES", __DIR__."/../themes/services");
define("EAGATA", "http://www.smf.maceio.al.gov.br:8090/e-agata/servlet/awstaxaexternas");
define("PERTENCES", "http://www3.smf.maceio.al.gov.br/e-agata/servlet/apwsretornopertences");
define("BOLETOS", "http://www.smf.maceio.al.gov.br:8090/e-agata/servlet/hwmemitedamqrcode?");
define("SITE", "#Orditi");
define("EMAIL", "contato@orditi.com");
define("COMPANY", "Orditi");

/**
 * Database config
 */
define("DATA_LAYER_CONFIG", [
    "driver" => "mysql",
    "host" => "localhost",
    "port" => "3306",
    "dbname" => "odtteste",
    "username" => "root",
    "passwd" => "",
    "options" => [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ]
]);

/**
 * Email config
 */
//define("MAIL", [
//    "host" => "orditi.com",
//    "port" => "465",
//    "user" => "contato@orditi.com",
//    "passwd" => "UU@T@fMn1M-F",
//    "from_name" => "Orditi",
//    "from_email" => "contato@orditi.com"
//]);

define("MAIL", [
    "host" => "smtp.gmail.com",
    "port" => "587",
    "user" => "orditibrasil@gmail.com",
    "passwd" => "c40028922",
    "from_name" => "Orditi",
    "from_email" => "orditibrasil@gmail.com"
]);

/**
 * @param string|null $uri
 * @return string
 */
function url(string $uri = null): string
{
    if ($uri) {
        return ROOT . "/{$uri}";
    }

    return ROOT;
}
