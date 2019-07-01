<?php

require_once("class/model/Entity.php");

require_once("generate/GenerateFileEntity.php");


class ClassValues extends GenerateFileEntity {


  public function __construct(Entity $entity) {
    $directorio = PATH_ROOT."src/class/model/values/" . $entity->getName("xxYy") . "/";
    $nombreArchivo = $entity->getName("XxYy").".php";
    parent::__construct($directorio, $nombreArchivo, $entity);
  }

  protected function generateCode() {
    $this->string .= "<?php

require_once(\"class/model/values/" . $this->getEntity()->getName("xxYy") . "/Main.php\");

class " . $this->getEntity()->getName("XxYy") . "Values extends " . $this->getEntity()->getName("XxYy") . "ValuesMain{

}

";
  }


}
