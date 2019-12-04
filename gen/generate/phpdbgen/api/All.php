<?php

require_once("class/model/Entity.php");

require_once("generate/GenerateFileEntity.php");


class Gen_AllApi extends GenerateFileEntity {

  public function __construct(Entity $entity) {
    $directorio = PATH_ROOT."src/class/api/all/";
    $nombreArchivo = $entity->getName("XxYy").".php";
    parent::__construct($directorio, $nombreArchivo, $entity);
  }

  protected function generateCode() {
    $this->string .= "<?php

require_once(\"class/api/All.php\");

class " . $this->getEntity()->getName("XxYy") . "AllApi extends AllApi {
  protected \$entityName = \"" . $this->getEntity()->getName() . "\";
}

";
  }


}
