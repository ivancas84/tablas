  <?php

require_once("./config/config.php");
require(PATH_GEN); //configuracion del modulo de administracion

require_once("config/structure.php");


require("generate/angular2/Angular2.php");
$php = new GenerateAngular2($structure);
$php->generate();

