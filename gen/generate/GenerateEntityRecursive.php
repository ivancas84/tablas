<?php


abstract class GenerateEntityRecursive extends GenerateEntity{ //Comportamiento comun recursivo
  public $hasRelations = false;

  public function generate(){
    if($this->hasRelations && !$this->getEntity()->hasRelations()) return "";

    $this->start();
    $this->recursive($this->getEntity());
    $this->end();

    return $this->string;
  }

  abstract protected function start();
  abstract protected function end();
  abstract protected function body(Entity $entity, $prefix);


  protected function recursive(Entity $entity, array $tablesVisited = [], $prefix = ""){ //Metodo recursivo de generacion de codigo
    /**
     * Genera codigo solo para las relaciones
     * @param Entity $entity
     * @param array $tablesVisited
     * @param type $prefix
     * @return type
     */

    if (!empty($prefix)) $this->string .= $this->body($entity, $prefix); //Genera codigo solo para las relaciones
    /**
     * Para determinar que se este recorriendo una relacion en vez de la entidad actual, se utiliza el prefix
     * Si prefix esta vacio, significa que recien se comenzo a recorrer la tabla actual y no se debe generar el codigo
     * Si prefix no esta vacio, ya se esta recorriendo una relacion y debe generarse codigo
     * El codigo para la entidad actual se genera fuera de este metodo
     */

    $this->fk($entity, $tablesVisited, $prefix);
    $this->u_($entity, $tablesVisited, $prefix);
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
