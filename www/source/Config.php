<?php

define("ROOT", "https://localhost");
define("THEMES", __DIR__."/../themes");
define("SITE", "#PROJETO");

/**
 * Database config
 */
define("DATA_LAYER_CONFIG", [
    "driver" => "mysql",
    "host" => "mysql-server",
    "port" => "3306",
    "dbname" => "taskList",
    "username" => "root",
    "passwd" => "secret",
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
