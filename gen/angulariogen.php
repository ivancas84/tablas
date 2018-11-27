<?php

//controlador para generar el proyecto AngularIoGen
require($_SERVER["DOCUMENT_ROOT"] . "/fines2/programacion/api/config/config.php"); //configuracion del modulo de administracion

require_once("config/structure.php");


require("generate/angulariogen/AngularIoGen.php");
$php = new AngularIoGen($structure);
$php->generate();
