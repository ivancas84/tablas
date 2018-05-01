  <?php

require_once("./config/config.php");
require(PATH_GEN); //configuracion del modulo de administracion


require_once("generate/config/Config.php");
$gen = new GenerateConfig();
$gen->generate();

