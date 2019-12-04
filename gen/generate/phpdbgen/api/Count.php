<?php

require_once("class/model/Entity.php");

require_once("generate/GenerateFileEntity.php");


class Gen_CountApi extends GenerateFileEntity {

  public function __construct(Entity $entity) {
    $directorio = PATH_ROOT."src/class/api/count/";
    $nombreArchivo = $entity->getName("XxYy").".php";
    parent::__construct($directorio, $nombreArchivo, $entity);
  }

  protected function generateCode() {
    $this->string .= "<?php

require_once(\"class/api/Count.php\");

class " . $this->getEntity()->getName("XxYy") . "CountApi extends CountApi {
  protected \$entityName = \"" . $this->getEntity()->getName() . "\";
}

";
  }


}
