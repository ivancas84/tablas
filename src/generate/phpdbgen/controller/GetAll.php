<?php

require_once("class/model/Entity.php");

require_once("generate/GenerateFileEntity.php");


class Gen_GetAll extends GenerateFileEntity {

  public function __construct(Entity $entity) {
    $directorio = PATH_ROOT."/class/controller/getAll/";
    $nombreArchivo = $entity->getName("XxYy").".php";
    parent::__construct($directorio, $nombreArchivo, $entity);
  }

  protected function generateCode() {
    $this->string .= "<?php

require_once(\"class/controller/GetAll.php\");

class " . $this->getEntity()->getName("XxYy") . "GetAll extends GetAll {
  public \$entityName = \"" . $this->getEntity()->getName() . "\";
}

";
  }


}
