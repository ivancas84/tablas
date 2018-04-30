<?php

require($_SERVER["DOCUMENT_ROOT"] . "/fines2/comisiones/api/config/config.php"); //configuracion del modulo de administracion


require_once("generate/config/Config.php");
$gen = new GenerateConfig();
$gen->generate();

//incluimos la estructura para utilizarla en las clases de generacion
//require_once("config/structure.php");


//por cada lenguaje utilizado se define una clase de generacion de codigo
//require("generate/php/Php.php");
//$php = new GeneratePhp($structure);
//$php->generate();

//require("generate/angular2/Angular2.php");
//$angular2 = new GenerateAngular2($structure);
//$angular2->generate();
