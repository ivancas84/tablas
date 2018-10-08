<?php

require_once("generate/GenerateFileEntity.php");


class TypescriptEntity extends GenerateFileEntity {

  public function __construct(Entity $entity, $directorio = null){
    $file = $entity->getName("xx-yy") . ".ts";
    if(!$directorio) $directorio = PATH_ROOT . "src/app/class/entity/{$entity->getName("xx-yy")}/";
    parent::__construct($directorio, $file, $entity);
  }


  public function generateCode() {
    $this->string .= "import { {$this->entity->getName('XxYy')}Main } from './{$this->entity->getName('xx-yy')}-main';

export class {$this->entity->getName('XxYy')} extends {$this->entity->getName('XxYy')}Main {

}

";
  }


  protected function start(){

  }

  protected function properties(){
    require_once("generate/angulariogen/class/entity/_Properties.php");
    $gen = new TypescriptEntity_properties($this->entity);
    $this->string .= $gen->generate();
  }


  protected function initJson(){
    require_once("generate/angulariogen/class/entity/_InitJson.php");
    $gen = new TypescriptEntity_initJson($this->entity);
    $this->string .= $gen->generate();
  }

  protected function end(){
    $this->string .= "}
";
  }









}
