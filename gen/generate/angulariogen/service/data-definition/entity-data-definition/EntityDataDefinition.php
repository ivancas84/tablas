<?php

require_once("generate/GenerateFileEntity.php");

class EntityDataDefinition extends GenerateFileEntity {

  public function __construct(Entity $entity) {
    $dir = PATH_GEN."src/app/service/data-definition/" . $entity->getName("xx-yy") . "/";
    $file = $entity->getName("xx-yy") . "-data-definition.ts";
    parent::__construct($dir, $file, $entity);
  }


  protected function generateCode(){
    $this->string .= "import { " . $this->entity->getName("XxYy") . "DataDefinitionMain } from './" . $this->entity->getName("xx-yy") . "-data-definition-main';

export class " . $this->entity->getName("XxYy") . "DataDefinition extends " . $this->entity->getName("XxYy") . "DataDefinitionMain {

}
";
  }


}
