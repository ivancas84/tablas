<?php

require_once("class/model/Entity.php");

require_once("generate/GenerateFileEntity.php");


class Gen_Unique extends GenerateFileEntity {

  public function __construct(Entity $entity) {
    $directorio = PATH_ROOT."src/class/controller/unique/";
    $nombreArchivo = $entity->getName("XxYy").".php";
    parent::__construct($directorio, $nombreArchivo, $entity);
  }

  protected function generateCode() {
    $this->string .= "<?php

require_once(\"class/controller/Unique.php\");

class " . $this->getEntity()->getName("XxYy") . "Unique extends Unique {
  public \$entityName = \"" . $this->getEntity()->getName() . "\";
}

";
  }


}
