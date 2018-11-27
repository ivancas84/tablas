<?php

//controlador para generar el proyecto PhpDbGen
require($_SERVER["DOCUMENT_ROOT"] . "/fines2/programacion/api/config/config.php"); //configuracion del modulo de administracion

require_once("config/structure.php");


require("generate/phpdbgen/PhpDbGen.php");
$php = new PhpDbGen($structure);
$php->generate();
