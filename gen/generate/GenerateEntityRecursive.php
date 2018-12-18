<?php


//Comportamiento comun recursivo
abstract class GenerateEntityRecursive extends GenerateEntity{

  public function generate(){
    $this->start();
    $this->recursive($this->getEntity());
    $this->end();

    return $this->string;
  }

  abstract protected function start();
  abstract protected function end();
  abstract protected function body(Entity $entity, $prefix);

  /**
   * Metodo recursivo de generacion de codigo
   * @param Entity $entity
   * @param array $tablesVisited
   * @param type $prefix
   * @return type
   */
  protected function recursive(Entity $entity, array $tablesVisited = NULL, $prefix = ""){
    if (is_null($tablesVisited)) $tablesVisited = array();

    if (!empty($prefix)){
      $this->string .= $this->body($entity, $prefix);
    }

    $this->fk($entity, $tablesVisited, $prefix);
    if(empty($prefix)) $this->u_($entity, $tablesVisited, $prefix);
  }

  public function fk(Entity $entity, array $tablesVisited, $prefix){
    $fk = $entity->getFieldsFkNotReferenced($tablesVisited);
    $prf = (empty($prefix)) ? "" : $prefix . "_";
    array_push($tablesVisited, $entity->getName());

    foreach($fk as $field){
      $this->recursive($field->getEntityRef(), $tablesVisited, $prf . $field->getAlias());
    }
  }

  protected function u_(Entity $entity, array $tablesVisited, $prefix){
    $u_ = $entity->getFieldsU_NotReferenced($tablesVisited);
    $prf = (empty($prefix)) ? "" : $prefix . "_";
    array_push($tablesVisited, $entity->getName());

    foreach($u_ as $field) {
      $this->body($field->getEntity(), $prf . $field->getAlias("_"));
    }
  }
}
