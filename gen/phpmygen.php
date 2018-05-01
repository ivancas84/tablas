  <?php

require_once("./config/config.php");
require(PATH_GEN); //configuracion del modulo de administracion

require_once("config/structure.php");


require("generate/phpmygen/Php.php");
$php = new GeneratePhp($structure);
$php->generate();

