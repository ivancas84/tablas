<?php

require_once("GenerateEntity.php");

class Sqlo_values extends GenerateEntity { //deberia extender de GenerateEntityRecursiveFk pero modifica algunos metodos

  protected $names = [];
  
  protected function hasRelations(){ return ($this->getEntity()->hasRelations()) ? true : false; }

  protected function defineName($name){
    if (!in_array($name, $this->names)){
      array_push($this->names, $name);
      return $name;
    } else {
      $match = preg_split('/(?<=\D)(?=\d)/', $name);
      $name = (count($match) < 2) ? $name . "1" :  $match[0] . strval((intval($match[1]) + 1));
      return $this->defineName($name);
    }
  }

  public function generate(){
    if(!$this->hasRelations()) return "";

    $this->start();
    $this->recursive($this->getEntity());
    $this->end();

    return $this->string;
  }

  
  
  protected function start(){
    $e = $this->defineName($this->getEntity()->getName());

    $this->string .= "  public function values(array \$row){
    \$row_ = [];

    \$row_[\"{$e}\"] = EntityValues::getInstanceRequire(\"{$this->getEntity()->getName()}\", \$row);
";
  }


  protected function body(Entity $entity, $prefix, $name){
    $this->string .= "    \$row_[\"{$name}\"] = EntityValues::getInstanceRequire('{$entity->getName()}', \$row, '{$prefix}_');
";
  }



    protected function end(){
      $this->string .= "    return \$row_;
  }

";
    }


    protected function recursive(Entity $entity, array $tablesVisited = NULL, $prefix = "", $name = "") {
      if (is_null($tablesVisited)) $tablesVisited = array();
  
      if(in_array($entity->getName(), $tablesVisited)) return;
      
      if (!empty($prefix))  {
        $this->string .= $this->body($entity, $prefix, $name); //Genera codigo solo para las relaciones
      }
  
  
      $this->fk($entity, $tablesVisited, $prefix);
  
  
    }

    public function fk(Entity $entity, array $tablesVisited, $prefix){
      $fk = $entity->getFieldsFkNotReferenced($tablesVisited);
      $prf = (empty($prefix)) ? "" : $prefix . "_";
      array_push($tablesVisited, $entity->getName());
  
      foreach($fk as $field){
        $name = $this->defineName($field->getName());
        $this->recursive($field->getEntityRef(), $tablesVisited, $prf . $field->getAlias(), $name);
      }
    }
  

}
