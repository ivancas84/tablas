<?php


class ClassSql__conditionAdvanced extends GenerateEntity{

  protected function start(){
    $this->string .= "  //@override
    protected function conditionAdvancedMain(\$field, \$option, \$value){
      switch (\$field){
  ";
  }

  public function generate(){
    $this->start();
    $this->condition($this->getEntity(), $this->getEntity()->getAlias(), "");
    $this->recursive($this->getEntity());
    $this->end();

    return $this->string;
  }


  protected function string($fieldName, $tableAlias, $fieldAlias){
    $this->string .= "      case \"" . $fieldAlias . $fieldName . "\": return \$this->conditionText(\"" . $tableAlias . "." . $fieldName . "\", \$value, \$option);
" ;

  }


  protected function number($fieldName, $tableAlias, $fieldAlias){
    $this->string .= "      case \"" . $fieldAlias . $fieldName . "\": return \$this->conditionNumber(\"" . $tableAlias . "." . $fieldName . "\", \$value, \$option);
" ;
	}

  protected function date($fieldName, $tableAlias, $fieldAlias){
    $this->string .= "      case \"" . $fieldAlias . $fieldName . "\": return \$this->conditionDate(\"" . $tableAlias . "." . $fieldName . "\", \$value, \$option);
" ;
  }

  protected function boolean($fieldName, $tableAlias, $fieldAlias){
    $this->string .= "      case \"" . $fieldAlias . $fieldName . "\": return \$this->conditionBoolean(\"" . $tableAlias . "." . $fieldName . "\", \$value);
" ;
  }




  protected function condition(Entity $entity, $tableAlias, $fieldAlias){



    foreach ( $entity->getFields() as $field) {
      switch ( $field->getDataType() ) {
        case "string":
        case "text": $this->string($field->getName(), $tableAlias, $fieldAlias); break;
        case "integer":
        case "float": $this->number($field->getName(), $tableAlias, $fieldAlias); break;
        case "boolean": $this->boolean($field->getName(), $tableAlias, $fieldAlias); break;
        case "date": $this->date($field->getName(), $tableAlias, $fieldAlias); break;
      }
    }
    unset($field);
  }



  protected function end(){
    $this->string .= "    }
  }
";
  }

  protected function fk(Entity $entity, array $tablesVisited, $prefix){
    $fk = $entity->getFieldsFkNotReferenced($tablesVisited);

    foreach($fk as $field){
      $fieldAlias = $prefix . $field->getAlias();

      $this->condition($field->getEntityRef(), $fieldAlias, $fieldAlias."_");
      $this->recursive($field->getEntityRef(), $tablesVisited, $fieldAlias . "_");
    }
  }

  protected function u_(Entity $entity, array $tablesVisited, $prefix){
    $u_ = $entity->getFieldsU_NotReferenced($tablesVisited);

    foreach($u_ as $field) {
      $fieldAlias = $prefix . $field->getAlias("_");

      $this->condition($field->getEntity(), $fieldAlias, $fieldAlias."_");
      //$this->recursive($field->getEntity(), $tablesVisited, $fieldAlias . "_");
    }
  }

  /**
   * Metodo recursivo de generacion de codigo
   * @param Entity $entity
   * @param array $tablesVisited
   * @param type $prefix
   * @return type
   */
  protected function recursive(Entity $entity, array $tablesVisited = NULL, $prefix = ""){
    if (is_null($tablesVisited)) $tablesVisited = array();
    array_push($tablesVisited , $entity->getName());

    $this->fk($entity, $tablesVisited, $prefix);
    $this->u_($entity, $tablesVisited, $prefix);
  }





}
