<?php

require_once("generate/php/sql/method/fields/Fields.php");

class ClassSql_fieldsLabelFull extends GenerateEntity{
  
  protected function end(){
    $this->string .= "    \";
  }
";
  }
  
  public function fieldsConcat(Entity $entity, $prefixTable = "", $prefixField = ""){    
    $tableIdentification = (!empty($prefixTable)) ? $prefixTable : $entity->getAlias();

    $mf = $entity->getFieldsByType(array("pk","nf"));

    $this->string .= "CONCAT_WS(', ', ";

    $existMain = false;
    foreach ( $mf as $field ) {
      if($field->isMain()) {
        $existMain = true;
        $this->string .= $tableIdentification . "." . $field->getName() . ", ";
      }
    }
    
    if(!$existMain) $this->string .= $tableIdentification . ".id, ";

    
    $this->string = rtrim($this->string);
    $this->string = rtrim($this->string, ", ");
    $this->string .= ") AS " . $prefixField . "label,
";
  }

  protected function start(){
    $this->string .= "
  //***** @override *****
  public function fieldsLabelFull(){
    return \"";
  }
  
  protected function fk(Entity $entity, array $tablesVisited, $prefixTable, $prefixField){
    $fk = $entity->getFieldsFkNotReferenced($tablesVisited);
    foreach ($fk as $field ) {  
      array_push($tablesVisited, $entity->getName());
      $this->fieldsConcat($field->getEntityRef(), $prefixTable . $field->getAlias(), $prefixField . $field->getAlias() . "_") ;
      $this->recursive($field->getEntityRef(), $tablesVisited, $prefixTable . $field->getAlias() . "_", $prefixField . $field->getAlias() . "_");
    }
  }
  
  protected function u_(Entity $entity, array $tablesVisited, $prefixTable, $prefixField){
    $u_ = $entity->getFieldsU_NotReferenced($tablesVisited);

    foreach ($u_ as $field ) {
      array_push($tablesVisited, $entity->getName());
      $this->string .= $this->fieldsConcat($field->getEntity(), $prefixTable . $field->getAlias("_"), $prefixField . $field->getAlias("_") . "_");
    }

  }
  

  protected function recursive(Entity $entity, array $tablesVisited = NULL, $prefixTable = "", $prefixField = "") {
    if(is_null($tablesVisited)) $tablesVisited = array();
    $this->fk($entity, $tablesVisited, $prefixTable, $prefixField);
    $this->u_($entity, $tablesVisited, $prefixTable, $prefixField);
  }


  public function generate(){
    if(!$this->getEntity()->hasRelations()) return "";
    $this->start();
    $this->recursive($this->getEntity());
    $this->end();  
    return $this->string;
  }
  

}
