<?php

require_once("class/model/Entity.php");

require_once("GenerateFileEntity.php");


class Gen_All extends GenerateFileEntity {

  public function __construct(Entity $entity) {
    $directorio = $_SERVER["DOCUMENT_ROOT"]."/".PATH_ROOT."/class/controller/all/";
    $nombreArchivo = $entity->getName("XxYy").".php";
    parent::__construct($directorio, $nombreArchivo, $entity);
  }

  protected function generateCode() {
    $this->string .= "<?php

require_once(\"class/controller/All.php\");

class " . $this->getEntity()->getName("XxYy") . "All extends All {
  public \$entityName = \"" . $this->getEntity()->getName() . "\";
}

";
  }


}
