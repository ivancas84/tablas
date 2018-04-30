<?php



class ClassSql_conditionSearch extends GenerateEntity{
    
  protected $or = false; //boolean. Flag para indicar si existe condicion de busqueda
  

  public function defineOr() {
    if(!$this->or){
      $this->or = true;
      return false;
    }
    return true;
  }

  
  protected function fk(Entity $entity, array $tablesVisited, $prefix){
    $fk = $entity->getFieldsFkNotReferenced($tablesVisited);

    foreach ($fk as $field ) {  
      array_push($tablesVisited, $entity->getName());
      $this->recursive($field->getEntityRef(), $tablesVisited, $prefix . $field->getAlias()) ;
    }
  }
  
  protected function u_(Entity $entity, array $tablesVisited, $prefix){
    $u_ = $entity->getFieldsU_NotReferenced($tablesVisited);
    
    foreach ($u_ as $field ) {
      array_push($tablesVisited, $entity->getName());
      $this->condition($field->getEntity(), $prefix . $field->getAlias("_"));
      //$this->recursive($field->getEntity() , $tablesVisited, $prefix . $field->getAlias("_")) ;
    }
  }
  
  /**
   * Generar metodo para definir sql
   * @param string &$string Codigo generado
   * @param array $table Tabla de la estructura
   * @param array $tablesVisited Tablas visitadas para controlar recursion
   * @param string $prefix Prefijo de identificacion
   */
  protected function recursive(Entity $entity, array $tablesVisited = NULL, $prefix = ""){
    if (is_null($tablesVisited)) $tablesVisited = array();
    array_push($tablesVisited , $entity->getName());

    if (!empty($prefix)){
      $this->condition($entity, $prefix);
      $prefix = $prefix . "_";
    }
    
    $this->fk($entity, $tablesVisited, $prefix);
    $this->u_($entity, $tablesVisited, $prefix);
    
    unset ($prefix, $entity , $tablesVisited);
  }


  
  
  protected function start(){
    $this->string .= "
  //***** @override *****
  public function conditionSearch(\$search = \"\"){
    if(empty(\$search)) return '';
    \$condition = \"\";

";
  }
 
  
  protected function end(){
    $this->string .= "    return \"(\" . \$condition . \")\";
  }

";
  }
  
  protected function text($fieldName, $alias){
    $or = ($this->defineOr()) ? " OR " : "";

    $this->string .= "    \$condition .= \"". $or . "\" . \$this->_conditionTextApprox(\"" . $alias . "." . $fieldName . "\", \$search);
" ;
    
  }
  
  protected function number($fieldName, $alias){
    $or = ($this->defineOr()) ? " OR " : "";

    $this->string .= "    \$condition .= \"". $or . "\" . \$this->_conditionNumberApprox(\"" . $alias . "." . $fieldName . "\", \$search);
" ;
  }
  
  protected function date($fieldName, $alias){
    $or = ($this->defineOr()) ? " OR " : "";

    $this->string .= "    \$condition .= \"". $or . "\" . \$this->_conditionDateApprox(\"" . $alias . "." . $fieldName . "\", \$search);
" ;
  }
  
  protected function year($fieldName, $alias){
    $or = ($this->defineOr()) ? " OR " : "";

    $this->string .= "    \$condition .= \"". $or . "\" . \$this->_conditionYearApprox(\"" . $alias . "." . $fieldName . "\", \$search);
" ;
  }
  
  protected function timestamp ($fieldName, $alias){
    $or = ($this->defineOr()) ? " OR " : "";


    $this->string .= "    \$condition .= \"". $or . "\" . \$this->_conditionTimestampApprox(\"" . $alias . "." . $fieldName . "\", \$search);
" ;
  }
  

  
  protected function condition(Entity $entity, $alias){
    $fields = $entity->getFields();
    
    foreach ($fields as $field) {
      switch ($field->getDataType()) {
        case "string": case "text": $this->text($field->getName(), $alias); break;

        case "integer": case "float":
          $this->number($field->getName(), $alias);
        break;

        case "date": $this->date($field->getName(), $alias); break;
    
        case "year": $this->year($field->getName(), $alias); break;
        case "timestamp": $this->timestamp($field->getName(), $alias); break;
      }
    }
  }
  
  
  
  public function generate(){
    $this->start();
    $this->condition($this->getEntity(), $this->getEntity()->getAlias());
    $this->recursive($this->getEntity());
    $this->end();
    return $this->string;
  }
  


  
}
