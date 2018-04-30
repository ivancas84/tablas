<?php

/**
 * Notas:
 *    No confundir la ubicacion de los archivos en la generacion con la ubicacion de las clases generadas
 */
//***** inicializar *****
require_once("./config/config.php");

require_once(PATH_GEN); //configuracion del modulo de administracion


//structure: las clases de configuracion del modelo deben generarse siempre porque son utilizadas para la generacion de codigo en todos los lenguajes
require_once("generate/config/Config.php");
$gen = new GenerateConfig();
$gen->generate();

