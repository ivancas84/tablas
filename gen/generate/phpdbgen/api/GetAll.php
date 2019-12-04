<?php

require_once("class/model/Entity.php");

require_once("generate/GenerateFileEntity.php");


class Gen_GetAllApi extends GenerateFileEntity {

  public function __construct(Entity $entity) {
    $directorio = PATH_ROOT."src/class/api/getAll/";
    $nombreArchivo = $entity->getName("XxYy").".php";
    parent::__construct($directorio, $nombreArchivo, $entity);
  }

  protected function generateCode() {
    $this->string .= "<?php

require_once(\"class/api/GetAll.php\");

class " . $this->getEntity()->getName("XxYy") . "GetAllApi extends GetAllApi {
  protected \$entityName = \"" . $this->getEntity()->getName() . "\";
}

";
  }


}
