<?php

require_once("class/model/Entity.php");

require_once("generate/GenerateFileEntity.php");


class Gen_IdsApi extends GenerateFileEntity {

  public function __construct(Entity $entity) {
    $directorio = PATH_ROOT."src/class/api/ids/";
    $nombreArchivo = $entity->getName("XxYy").".php";
    parent::__construct($directorio, $nombreArchivo, $entity);
  }

  protected function generateCode() {
    $this->string .= "<?php

require_once(\"class/api/Ids.php\");

class " . $this->getEntity()->getName("XxYy") . "IdsApi extends IdsApi {
  protected \$entityName = \"" . $this->getEntity()->getName() . "\";
}

";
  }


}
