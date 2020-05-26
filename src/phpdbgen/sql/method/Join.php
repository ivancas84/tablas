<?php

require_once("GenerateEntity.php");

class ClassSql_join extends GenerateEntity {

  public function generate(){
    if(!$this->getEntity()->hasRelationsFk()) return "";

    $this->start();
    $this->recursive($this->getEntity());
    $this->end();

    return $this->string;
  }


 protected function start(){
    $this->string .= "  public function join(Render \$render){
    return ";

  }

  protected function end(){
    $pos = strrpos($this->string, ".");
    $this->string = substr_replace($this->string , ";" , $pos, 3);
    $this->string .= "
  }

";
  }

  protected function fk(array $fk, array $tablesVisited, $tableAux, $prefixAux){
    foreach ($fk as $field ) {
      $pk = $field->getEntityRef()->getPk();
      $prefixTemp = $prefixAux . $field->getAlias();


        $this->string .= "EntitySql::getInstanceRequire('{$field->getEntityRef()->getName()}', '{$prefixTemp}')->_join('{$field->getName()}', '{$tableAux}', \$render) . '
' . ";

      if(!in_array($field->getEntityRef()->getName(), $tablesVisited)) {

        $this->recursive($field->getEntityRef(), $tablesVisited, $prefixTemp);
      }


    }
  }

  protected function recursive(Entity $entity, array $tablesVisited = NULL, $prefix = ""){
    if(is_null($tablesVisited)) $tablesVisited = array();
    array_push ($tablesVisited, $entity->getName());
    $fk = $entity->getFieldsFkNotReferenced($tablesVisited);
    //$u_ = $entity->getFieldsU_NotReferenced($tablesVisited);

    if (empty ($prefix)){
      $prefixAux = "";
      $tableAux = $entity->getAlias();
    } else {
      $prefixAux = $prefix . "_";
      $tableAux = $prefix;
    }

    $this->fk($fk, $tablesVisited, $tableAux, $prefixAux);
  }




}
