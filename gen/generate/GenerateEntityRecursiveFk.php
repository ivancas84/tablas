<?php
require_once("generate/GenerateEntityRecursive.php");

//Comportamiento comun recursivo
abstract class GenerateEntityRecursiveFk extends GenerateEntityRecursive{

  /**
   * Metodo recursivo de generacion de codigo
   * @param Entity $entity
   * @param array $tablesVisited
   * @param type $prefix
   * @return type
   */
  protected function recursive(Entity $entity, array $tablesVisited = NULL, $prefix = ""){
    if (is_null($tablesVisited)) $tablesVisited = array();

    $this->string .= $this->body($entity, $prefix);
    $this->fk($entity, $tablesVisited, $prefix);
  }
}
