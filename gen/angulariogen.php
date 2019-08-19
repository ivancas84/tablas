<?php

//controlador para generar el proyecto AngularIoGen

//configuracion general
require($_SERVER["DOCUMENT_ROOT"] . "/dea-organizacion/src/config/config.php"); //configuracion del modulo de administracion

//constante de destino de archivos generados
define("PATH_GEN" ,  $_SERVER["DOCUMENT_ROOT"] . "/fines2/comisiones/");

require_once("config/structure.php");


require("generate/angulariogen/AngularIoGen.php");
$php = new AngularIoGen($structure);
$php->generate();
