<?php

require_once("class/model/Entity.php");

require_once("GenerateFileEntity.php");


class ClassValues extends GenerateFileEntity {


  public function __construct(Entity $entity) {
    $directorio = $_SERVER["DOCUMENT_ROOT"]."/".PATH_ROOT."/class/model/values/" . $entity->getName("xxYy") . "/";
    $nombreArchivo = $entity->getName("XxYy").".php";
    parent::__construct($directorio, $nombreArchivo, $entity);
  }

  protected function generateCode() {
    $this->string .= "<?php

require_once(\"class/model/values/" . $this->getEntity()->getName("xxYy") . "/_" . $this->getEntity()->getName("XxYy") . ".php\");

class " . $this->getEntity()->getName("XxYy") . " extends _" . $this->getEntity()->getName("XxYy") . "{}

";
  }


}
