<?php

//controlador para generar la estructura php de mapeo de base de datos
require($_SERVER["DOCUMENT_ROOT"] . "/dea-organizacion/src/config/config.php"); //configuracion del modulo de administracion


require_once("generate/tablas/Tablas.php");
$gen = new Tablas();
$gen->generate();
