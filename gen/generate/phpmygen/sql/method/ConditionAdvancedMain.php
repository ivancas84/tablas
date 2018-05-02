<?php


class ClassSql_conditionAdvancedMain extends GenerateEntity{


  public function generate(){
    $this->start();
    $this->recursive($this->getEntity());
    $this->end();

    return $this->string;
  }

  protected function start(){
    $this->string .= "  //@override
  protected function conditionAdvancedMain(\$field, \$option, \$value){
    self::_conditionAdvanced(\$field, \$option, \$value);
";
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

      if (!empty($prefix)){
        $this->string .= $this->condition($entity, $prefix);
      }

      $this->fk($entity, $tablesVisited, $prefix);
      $this->u_($entity, $tablesVisited, $prefix);
    }




  protected function condition(Entity $entity, $prefix){
    $this->string .= "    {$entity->getName("XxYy")}Sql::_conditionAdvanced(\$field, \$option, \$value, '{$prefix}')
";
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
      $this->condition($field->getEntity(), $prf . $field->getAlias("_"));
    }
  }


  protected function end(){
    $this->string .= "    }
    }
  ";
  }







}
