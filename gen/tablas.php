<?php

//controlador para generar la estructura php de mapeo de base de datos

require_once("./config/config.php");
require(PATH_GEN); //configuracion del modulo de administracion


require_once("generate/tablas/Tablas.php");
$gen = new Tablas();
$gen->generate();
