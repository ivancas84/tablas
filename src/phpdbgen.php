<?php

//controlador para generar el proyecto PhpDbGen
require($_SERVER["DOCUMENT_ROOT"] . "/config/config.php"); //configuracion del modulo de administracion

require_once("class/model/Entity.php");

require("generate/phpdbgen/PhpDbGen.php");
$php = new PhpDbGen(Entity::getStructure());
$php->generate();
