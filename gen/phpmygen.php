<?php

//controlador para generar el proyecto PhpMyGen

require_once("./config/config.php");
require(PATH_GEN); //configuracion del modulo de administracion

require_once("config/structure.php");


require("generate/phpmygen/PhpMyGen.php");
$php = new PhpMyGen($structure);
$php->generate();
