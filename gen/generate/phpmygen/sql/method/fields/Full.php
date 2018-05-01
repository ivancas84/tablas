<?php


class ClassSql_fieldsFull extends GenerateEntity {
  
  /**
  * Generar sql distinct fields
  * @param array $table Tabla de la estructura
  * @param string $string Codigo generado hasta el momento
  * @return string Codigo generado
  */
  protected function fields(Entity $entity, $tableName, $prefixField){
    $pkNfFk = $entity->getFields();
    foreach ( $pkNfFk as $field ) {
      $this->string .= $tableName . "." . $field->getName() . " AS " . $prefixField . $field->getName() . ", ";

    }
    
    $this->string .= "
";
  }
  
  protected function end(){
    $this->string .= "    \";
  }
";
  }
  
  protected function start(){
    $this->string .= "
  //***** @override *****
  public function fieldsFull(){
    return \$this->fields() . \"";
  }
  
  
  protected function fk(Entity $entity, array $tablesVisited, $prefixTable, $prefixField){
    $fk = $entity->getFieldsFkNotReferenced($tablesVisited);
    foreach ($fk as $field ) {  
      array_push($tablesVisited, $entity->getName());
      $this->string .= $this->recursive($field->getEntityRef(), $tablesVisited, $prefixTable . $field->getAlias(), $prefixField . $field->getAlias() . "_") ;
    }
  }
  
  protected function u_(Entity $entity, array $tablesVisited, $prefixTable, $prefixField){
    $u_ = $entity->getFieldsU_NotReferenced($tablesVisited);
    
    foreach ($u_ as $field ) {
      array_push($tablesVisited, $entity->getName());
      $this->string .= $prefixTable . $field->getAlias("_") . "." . $field->getEntity()->getPk()->getName() . " AS " . $prefixField . $field->getAlias("_") . ", ";     
      $this->fields($field->getEntity(), $prefixTable . $field->getAlias("_"), $prefixField . $field->getAlias("_") . "_");
    }

  }
  
  /**
   * Metodo auxiliar de generacion del body main
   * @param Entity $entity
   * @param array $tablesVisited
   * @param type $prefixTable
   * @param type $prefixField
   */
  protected function recursive(Entity $entity, array $tablesVisited = NULL, $prefixTable = "", $prefixField = "") {
    if(is_null($tablesVisited)) $tablesVisited = array();
    
    $string = "";
    if (!empty($prefixTable)){
      $this->string .= $this->fields($entity, $prefixTable, $prefixField);
      $prefixTable = $prefixTable . "_";
    } else {
      $prefixTable = "";
    }
    
    $string .= $this->fk($entity, $tablesVisited, $prefixTable, $prefixField);
    $string .= $this->u_($entity, $tablesVisited, $prefixTable, $prefixField);
    return $string;
  }
  
  
   public function generate(){
    if(!$this->getEntity()->hasRelations()) return "";

    $this->start();
    //$this->fields($this->getEntity(), $this->getEntity()->getAlias(), "");
    $this->recursive($this->getEntity());
    $this->end();  
    return $this->string;
  }
 
}
