<?php



class ClassSql_conditionSearch extends GenerateEntity{

  protected $or = false; //boolean. Flag para indicar si existe condicion de busqueda




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
    \$condition = \$this->_conditionSearch(\$search);

";
  }


  protected function end(){
    $this->string .= "    return \"(\" . \$condition . \")\";
  }

";
  }





  protected function condition(Entity $entity, $alias){
    $this->string .= "  \$condition .= \" OR \" . Dba::sql('{$entity->getName()}')->_conditionSearch(\$search, '{$alias}');
";
  }



  public function generate(){
    $this->start();
    $this->recursive($this->getEntity());
    $this->end();
    return $this->string;
  }




}
