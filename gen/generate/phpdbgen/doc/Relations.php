<?php


require_once("generate/GenerateEntityRecursiveFk.php");

class Doc_relations extends GenerateEntityRecursiveFk{

  protected function start(){
    $this->string .= "NO EDITAR ESTE ARCHIVO, LOS CAMBIOS SERÁN SOBRESCRITOS!
RELACIONES DE {$this->getEntity()->getName()}
";
  }


  protected function body(Entity $entity, $prefix){
    $this->string .= "{$entity->getName()} - {$prefix}
";
  }

  protected function end(){
    $this->string .= "";
  }







}
