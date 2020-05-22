<?php

require_once("class/model/Entity.php");

require_once("generate/GenerateFileEntity.php");


class Gen_DisplayRender extends GenerateFileEntity {

  public function __construct(Entity $entity) {
    $directorio = PATH_ROOT."/class/controller/displayRender/";
    $nombreArchivo = $entity->getName("XxYy").".php";
    parent::__construct($directorio, $nombreArchivo, $entity);
  }

  protected function generateCode() {
    $this->string .= "<?php

require_once(\"class/controller/DisplayRender.php\");

class " . $this->getEntity()->getName("XxYy") . "DisplayRender extends DisplayRender {
  public \$entityName = \"" . $this->getEntity()->getName() . "\";
}

";
  }


}
