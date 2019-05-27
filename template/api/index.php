<?php

require_once("config/config.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
//header("Access-Control-Allow-Headers: X-Requested-With");
header('Access-Control-Allow-Methods: GET, POST'); //solo se permite GET y POST, la opcion se define mediante la URL

//eader('Content-Type: text/html; charset=utf-8'); //debug
header('Content-Type: application/json; charset=utf-8'); //produccion

$param = explode('/', trim($_SERVER['REDIRECT_URL'], '/')); //si esta disponible, utilizar $_SERVER["PATH_INFO"]
$index = array_search("api", $param);

$options = array_slice($param, $index+1);


if(count($options) != 2) {
  http_response_code(400);
  return;
}

session_start();

//TESTING
session_id("1");

define("ENTITY", $options[0]);
$script = $options[1];


$inc = require("script/" . $script . ".php");
//if(!$inc) print_r($_REQUEST);
