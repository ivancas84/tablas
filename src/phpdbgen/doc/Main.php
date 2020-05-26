<?php

require_once("class/model/Entity.php");
require_once("GenerateFileEntity.php");

//Generar codigo de clase
class Doc extends GenerateFileEntity {

  public function __construct(Entity $entity) {
    $directorio = $_SERVER["DOCUMENT_ROOT"]."/".PATH_DOC."/relations/";
    $nombreArchivo = $entity->getName() . ".txt";
    parent::__construct($directorio, $nombreArchivo, $entity);
  }

  protected function generateCode(){
    $this->relations();
    $this->fields();
  }

  protected function relations(){
    require_once("phpdbgen/doc/Relations.php");
    $g = new Doc_relations($this->getEntity());
    $this->string .=  $g->generate();
  }

  protected function fields(){
    require_once("phpdbgen/doc/Fields.php");
    $g = new Doc_fields($this->getEntity());
    $this->string .=  $g->generate();
  }

}
