<?php

require_once("class/model/Entity.php");

require_once("generate/GenerateFileEntity.php");


class Gen_UniqueApi extends GenerateFileEntity {

  public function __construct(Entity $entity) {
    $directorio = PATH_ROOT."src/class/api/unique/";
    $nombreArchivo = $entity->getName("XxYy").".php";
    parent::__construct($directorio, $nombreArchivo, $entity);
  }

  protected function generateCode() {
    $this->string .= "<?php

require_once(\"class/api/Unique.php\");

class " . $this->getEntity()->getName("XxYy") . "UniqueApi extends UniqueApi {
  protected \$entityName = \"" . $this->getEntity()->getName() . "\";
}

";
  }


}
