<?php

require_once("generate/GenerateEntity.php");

class ClassSql_join extends GenerateEntity {

  public function generate(){
    if(!$this->getEntity()->hasRelationsFk()) return "";

    $this->start();
    $this->recursive($this->getEntity());
    $this->end();

    return $this->string;
  }

  
 protected function start(){
    $this->string .= "  public function join(){
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

      if(!in_array($field->getEntityRef()->getName(), $tablesVisited)) {

        $this->string .= "EntitySql::getInstanceFromString('{$field->getEntityRef()->getName()}', '{$prefixTemp}')->_join('{$field->getName()}', '{$tableAux}') . '
' . ";

        $this->recursive($field->getEntityRef(), $tablesVisited, $prefixTemp);
      }


    }
  }

  protected function u_(array $u_, array $tablesVisited, $tableAux, $prefixAux){
    foreach ($u_ as $field ) {
      $fieldAlias = $field->getAlias("_");

      $pk = $field->getEntityRef()->getPk();
      $prefixTemp = $prefixAux . $fieldAlias ;

      $this->string .= "EntitySql::getInstanceFromString('{$field->getEntity()->getName()}', '{$prefixTemp}')->_joinR('{$field->getName()}', '{$tableAux}') . '
' . ";

      //$this->string .= $this->recursive($field->getEntity(), $tablesVisited, $prefixTemp);

    }
    unset($u_, $field, $tablesVisited, $tableAux, $prefixAux, $pk, $prefixTemp, $fieldAlias);
  }

  protected function recursive(Entity $entity, array $tablesVisited = NULL, $prefix = ""){
    if(is_null($tablesVisited)) $tablesVisited = array();
    $fk = $entity->getFieldsFkNotReferenced($tablesVisited);
    //$u_ = $entity->getFieldsU_NotReferenced($tablesVisited);



    array_push ($tablesVisited, $entity->getName());

    if (empty ($prefix)){
      $prefixAux = "";
      $tableAux = $entity->getAlias();
    } else {
      $prefixAux = $prefix . "_";
      $tableAux = $prefix;
    }

    $this->fk($fk, $tablesVisited, $tableAux, $prefixAux);
    //$this->u_($u_, $tablesVisited, $tableAux, $prefixAux);

  }




}
