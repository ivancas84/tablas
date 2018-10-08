<?php

require_once("generate/GenerateFileEntity.php");


class TypescriptEntityMain extends GenerateFileEntity {

  public function __construct(Entity $entity, $directorio = null){
    $file = $entity->getName("xx-yy") . "-main.ts";
    if(!$directorio) $directorio = PATH_ROOT . "src/app/class/";
    parent::__construct($directorio, $file, $entity);
  }


  public function generateCode() {
    $this->start();
    $this->properties();
    $this->constructor();
    $this->end();
  }


  protected function start(){
    $this->string .= "export class " . $this->entity->getName("XxYy") . "Main extends Entity {
";
  }

  protected function properties(){
    require_once("generate/angulariogen/class/entity/_Properties.php");
    $gen = new TypescriptEntity_properties($this->entity);
    $this->string .= $gen->generate();
  }

  protected function constructor(){
    require_once("generate/angulariogen/class/entity/_Constructor.php");
    $gen = new TypescriptEntity_constructor($this->entity);
    $this->string .= $gen->generate();
  }


  protected function end(){
    $this->string .= "}
";
  }









}
