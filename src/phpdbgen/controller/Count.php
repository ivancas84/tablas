<?php

require_once("class/model/Entity.php");

require_once("GenerateFileEntity.php");


class Gen_Count extends GenerateFileEntity {

  public function __construct(Entity $entity) {
    $directorio = $_SERVER["DOCUMENT_ROOT"]."/".PATH_ROOT."/class/controller/count/";
    $nombreArchivo = $entity->getName("XxYy").".php";
    parent::__construct($directorio, $nombreArchivo, $entity);
  }

  protected function generateCode() {
    $this->string .= "<?php

require_once(\"class/controller/Count.php\");

class " . $this->getEntity()->getName("XxYy") . "Count extends Count {
  public \$entityName = \"" . $this->getEntity()->getName() . "\";
}

";
  }


}
