<?php

//controlador para generar el proyecto AngularIoGen
require_once("./config/config.php");
require(PATH_GEN); //configuracion del modulo de administracion

require_once("config/structure.php");


require("generate/angulariogen/AngularIoGen.php");
$php = new AngularIoGen($structure);
$php->generate();
