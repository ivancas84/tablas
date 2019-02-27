<?php

require_once("generate/GenerateEntityRecursiveFk.php");

class ClassSql_fieldsFull extends GenerateEntityRecursiveFk {
  public $fields = [];

  /*public function generate(){
    if(!$this->getEntity()->hasRelations()) return "";

    $this->start();
    $this->recursive($this->getEntity());
    $this->end();
    return $this->string;
  }*/


  protected function start(){
    $this->string .= "  public function fieldsFull(){
    return \$this->fields() . ',
' . ";
  }

  /**
  * Generar sql distinct fields
  * @param array $table Tabla de la estructura
  * @param string $string Codigo generado hasta el momento
  * @return string Codigo generado
  */
  protected function body(Entity $entity, $prefix){
    $this->string .= "EntitySql::getInstanceFromString('{$entity->getName()}', '{$prefix}')->fields() . ',
' . ";

  }

  protected function end(){
    $pos = strrpos($this->string, ",");
    $this->string = substr_replace($this->string , "" , $pos, 6);
    $this->string .= "
';
  }

";
  }







}
