<?php

require_once("GenerateEntity.php");

class Sqlo_json extends GenerateEntity {

   public function generate(){
    if (!$this->entity->hasRelations()) return;
    $this->start();
    $this->recursive($this->getEntity());
    $this->end();
    return $this->string;
  }

  protected function start(){
    $this->string .= "  public function json(array \$row = null){
    if(empty(\$row)) return null;
    \$row_ = \$this->sql->_json(\$row);
";
  }

  protected function recursive(Entity $entity, array $tablesVisited = NULL, $arrayName = "", $prefix = "") {
    if(is_null($tablesVisited)) $tablesVisited = array();

    if(in_array($entity->getName(), $tablesVisited)) return;

    if (!empty($arrayName)){
      $this->body($entity, $arrayName, $prefix);
    } else {
      $arrayName = "\$row_";
    }

    $this->fk($entity, $tablesVisited, $arrayName, $prefix);
  }

  protected function body(Entity $entity, $arrayName, $prefix){
    $this->string .= "    if(!is_null(\$row['{$prefix}_id'])){
      \$json = EntitySql::getInstanceRequire('{$entity->getName()}', '{$prefix}')->_json(\$row);
      " . $arrayName . " = \$json;
    }
";
  }


  protected function fk(Entity $entity, array $tablesVisited, $arrayName, $prefix){
    $fk = $entity->getFieldsFkNotReferenced($tablesVisited);
    foreach ($fk as $field ) {
      array_push($tablesVisited, $entity->getName());
      $p = (empty($prefix)) ? $field->getAlias() : $prefix . "_" . $field->getAlias();
      $this->recursive($field->getEntityRef(), $tablesVisited, $arrayName . "[\"" . $field->getName() . "_\"]", $p) ;
    }
  }


    protected function end(){
      $this->string .= "    return \$row_;
  }

";
    }



}
