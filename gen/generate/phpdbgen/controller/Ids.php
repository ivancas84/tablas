<?php

require_once("class/model/Entity.php");

require_once("generate/GenerateFileEntity.php");


class Gen_Ids extends GenerateFileEntity {

  public function __construct(Entity $entity) {
    $directorio = PATH_ROOT."src/class/controller/ids/";
    $nombreArchivo = $entity->getName("XxYy").".php";
    parent::__construct($directorio, $nombreArchivo, $entity);
  }

  protected function generateCode() {
    $this->string .= "<?php

require_once(\"class/controller/Ids.php\");

class " . $this->getEntity()->getName("XxYy") . "Ids extends Ids {
  public \$entityName = \"" . $this->getEntity()->getName() . "\";
}

";
  }


}
