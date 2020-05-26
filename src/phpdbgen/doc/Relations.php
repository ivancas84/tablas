<?php


require_once("GenerateEntityRecursiveFk.php");

class Doc_relations extends GenerateEntityRecursiveFk{

  protected function start(){
    $this->string .= "";
  }


  protected function body(Entity $entity, $prefix){
    $this->string .= "{$entity->getName()} - {$prefix}
";
  }

  protected function end(){
    $this->string .= "
";
  }







}
