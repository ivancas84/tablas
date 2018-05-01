<?php


class ClassSql_fieldsFull extends GenerateEntity {
  public $fields = [];

  public function generate(){
    if(!$this->getEntity()->hasRelations()) return "";

    $this->start();
    $this->recursive($this->getEntity());
    $this->end();
    return $this->string;
  }


  protected function start(){
    $this->string .= "  public function fieldsFull(){
    return self::_fields()";
  }

  /**
   * Metodo auxiliar de generacion del body main
   * @param Entity $entity
   * @param array $tablesVisited
   * @param type $prefix Prefijo, si esta vacio significa que se esta arrancando el metodo recursivo con la entidad principal
   * @param type $prefixField
   */
  protected function recursive(Entity $entity, array $tablesVisited = NULL, $prefix = "") {
    if(is_null($tablesVisited)) $tablesVisited = array();

    if (!empty($prefix)){
      $this->string .= $this->fields($entity, $prefix);
    }

    $this->fk($entity, $tablesVisited, $prefix);
    $this->u_($entity, $tablesVisited, $prefix);
  }


  /**
  * Generar sql distinct fields
  * @param array $table Tabla de la estructura
  * @param string $string Codigo generado hasta el momento
  * @return string Codigo generado
  */
  protected function fields(Entity $entity, $prefix){
    $field = "{$entity->getName("XxYy")}::_fields('{$prefix}')";
    array_push($this->fields, $field);
  }

  protected function end(){
    if(count($this->fields)){
      $this->string .= " .
    " .implode(' .
    ', $this->fields);
    }
    $this->string .= ";
  }
";
  }



  protected function fk(Entity $entity, array $tablesVisited, $prefix){
    $fk = $entity->getFieldsFkNotReferenced($tablesVisited);
    $prf = (empty($prefix)) ? "" : $prefix . "_";
    array_push($tablesVisited, $entity->getName());

    foreach ($fk as $field ) {
      $this->string .= $this->recursive($field->getEntityRef(), $tablesVisited, $prf . $field->getAlias()) ;
    }
  }

  protected function u_(Entity $entity, array $tablesVisited,  $prefix){
    $u_ = $entity->getFieldsU_NotReferenced($tablesVisited);
    $prf = (empty($prefix)) ? "" : $prefix . "_";
    array_push($tablesVisited, $entity->getName());

    foreach ($u_ as $field ) {
      $this->fields($field->getEntity(), $prf . $field->getAlias("_"));
    }

  }






}
