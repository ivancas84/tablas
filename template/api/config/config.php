<?php
ini_set("display_errors", 1);
ini_set('date.timezone', 'America/Argentina/Buenos_Aires');

//PRODUCCION
//session_set_cookie_params(7200, '/', '.dominio.com.ar');


define("SYS_NAME", "My App"); //nombre del modulo de administracion
define("PATH_SYS", "myapp"); //path correspondiente al módulo de administración

//constantes de acceso a la base de datos
define("DATA_DBNAME", "dbname");
define("DATA_USER", "dbuser");
define("DATA_PASS", "dbpass");
define("DATA_HOST", "localhost");
define("DATA_SCHEMA", "");
define("DISABLE_ENTITIES", "");

//raiz del modulo de administración
define("PATH_HTTP" , "http://" . $_SERVER["SERVER_NAME"] . "/" . PATH_SYS . "/");
define("PATH_ROOT" ,  $_SERVER["DOCUMENT_ROOT"] . "/" . PATH_SYS . "/");
//define("PATH_LOGIN", "http://" . $_SERVER["SERVER_NAME"] . "/" . PATH_SYS . "/login/"); //path correspondiente al módulo de login

//definición de rutas de inclusión
set_include_path(get_include_path()
  . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"] . "/" . PATH_SYS . "/api"
  . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"] . "/" . PATH_SYS . "/api/main"
);
