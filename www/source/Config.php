<?php

# define("ROOT", "https://intracking.space:81");
define("ROOT", "http://localhost:81");
define("THEMES", __DIR__."/../themes");

setlocale(LC_TIME, "pt_BR", "pt_BR.utf-8", "pt_BR.utf-8", "portuguese");
date_default_timezone_set("America/Sao_Paulo");
header('Access-Control-Allow-Origin: *');

/**
 * Database config
 */
define("DATA_LAYER_CONFIG", [
  "driver" => "mysql",
  "host" => "mysql-server",
  "port" => "2470",
  "dbname" => "intracking",
  "username" => "root",
  "passwd" => "secret",
  "options" => [
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    PDO::ATTR_CASE => PDO::CASE_NATURAL
  ]
]);
