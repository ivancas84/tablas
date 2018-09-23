<?php


class ClassSql_mappingField extends GenerateEntity{


  public function generate(){
    if (!$this->getEntity()->hasRelations()) return "";

    $this->start();
    $this->recursive($this->getEntity());
    $this->end();
    return $this->string;
  }

  protected function start(){
    $this->string .= "
  //@override
  public function mappingField(\$field){
    if(\$f = \$this->_mappingField(\$field)) return \$f;
";
  }


  protected function fk(Entity $entity, array $tablesVisited, $prefix){
    $fk = $entity->getFieldsFkNotReferenced($tablesVisited);
    $prefixAux = (empty($prefix)) ? "" : $prefix . "_";
    foreach ($fk as $field ) {
      array_push($tablesVisited, $entity->getName());
      $this->recursive($field->getEntityRef(), $tablesVisited, $prefixAux . $field->getAlias()) ;
    }
  }

  protected function u_(Entity $entity, array $tablesVisited, $prefix){
    $u_ = $entity->getFieldsU_NotReferenced($tablesVisited);
    $prefixAux = (empty($prefix)) ? "" : $prefix . "_";
    foreach ($u_ as $field ) {
      array_push($tablesVisited, $entity->getName());
      $this->mappingFieldRelation($field->getEntity(), $prefixAux . $field->getAlias("_"));
      //$this->recursive($field->getEntity() , $tablesVisited, $prefix . $field->getAlias("_") . "_") ;
    }
  }

  protected function recursive(Entity $entity, array $tablesVisited = NULL, $prefix = ""){
    if (is_null($tablesVisited)) $tablesVisited = array();
    array_push($tablesVisited , $entity->getName());

    if (!empty($prefix)){
      $this->mappingFieldRelation($entity, $prefix);
    }

    $this->fk($entity, $tablesVisited, $prefix);
    $this->u_($entity, $tablesVisited, $prefix);

  }





  protected function end(){
    $this->string .= "    throw new Exception(\"Campo no reconocido \" . \$field);
  }
";
  }



  protected function mappingFieldRelation(Entity $entity, $prefix) {
    $this->string .= "    if(\$f = Dba::sql('{$entity->getName()}', '" . $prefix . "')->_mappingField(\$field)) return \$f;
";
  }




}
