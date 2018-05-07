<?php

//controlador para generar el proyecto PhpDbGen

require_once("./config/config.php");
require(PATH_GEN); //configuracion del modulo de administracion

require_once("config/structure.php");


require("generate/phpdbgen/PhpDbGen.php");
$php = new PhpDbGen($structure);
$php->generate();
