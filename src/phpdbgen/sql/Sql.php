<?php

require_once("class/model/Entity.php");


class GenerateClassSql extends GenerateFileEntity {

  public function __construct(Entity $entity) {
    $dir = $_SERVER["DOCUMENT_ROOT"]."/".PATH_ROOT."/class/model/sql/" . $entity->getName("xxYy") . "/";
    $file = $entity->getName("XxYy").".php";
    parent::__construct($dir, $file, $entity);
  }
  
  
  //***** Generar codigo de clase *****
  protected function generateCode(){
        $this->string .= "<?php

require_once(\"class/model/sql/" . $this->getEntity()->getName("xxYy") . "/Main.php\");

class " . $this->getEntity()->getName("XxYy") . "Sql extends " . $this->getEntity()->getName("XxYy") . "SqlMain {

}
";
  }
  

}
