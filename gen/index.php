<?php

/**
 * Notas:
 *    No confundir la ubicacion de los archivos en la generacion con la ubicacion de las clases generadas
 */
//***** inicializar *****
require($_SERVER["DOCUMENT_ROOT"] . "/path/to/project/config/config.php"); //configuracion del modulo de administracion


//structure: las clases de configuracion del modelo deben generarse siempre porque son utilizadas para la generacion de codigo en todos los lenguajes
require_once("generate/config/Config.php");
$gen = new GenerateConfig();
$gen->generate();

//incluimos la estructura para utilizarla en las clases de generacion
require_once("config/structure.php");


//por cada lenguaje utilizado se define una clase de generacion de codigo
require("generate/php/Php.php");
$php = new GeneratePhp($structure);
$php->generate();

require("generate/angular2/Angular2.php");
$angular2 = new GenerateAngular2($structure);
//$angular2->generate();
