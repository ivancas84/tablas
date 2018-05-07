<?php


/**
 * Generar metodo para definir sql
 * @param array $table Tabla de la estructura
 * @param string &$string Codigo generado
 */
class ClassSql_fieldsLabel extends GenerateEntity{
	

  protected function isFeasibleNf(Entity $entity){
    $mf = $entity->getFieldsNf();
    foreach ($mf as $field){
      if($field->isMain()) return true;
    }

    return false;
  }

  protected function isFeasibleFk(Entity $entity, array $visitedTables = NULL){
    if(is_null($visitedTables)) $visitedTables = array();
    array_push($visitedTables, $entity->getName());

    $fk = $entity->getFieldsFkNotReferenced($visitedTables);

    foreach ($fk as $field){
      if($field->isMain()) return true;
    }

    return false;
  }

  /**
   * La generacion del metodo mainFieldsConcat se considera viable si existe al menos un field de la tabla distinto de la pk que esta marcado como principal
   */
  public function isFeasible(){
    $isFeasible = $this->isFeasibleNf($this->getEntity());

    if (!$isFeasible){
      $isFeasible = $this->isFeasibleFk($this->getEntity());

    }


    return $isFeasible;
  }

  /**
   * Metodo recursivo para generar codigo principal de los fields
   * @param Entity $entity Tabla de la estructura actualmente procesada
   * @param array $visitedTables Tablas visitadas para controlar la recursion
   * @param string $alias Alias de la fk actualmente procesada (si se esta procesando la tabla principal el alias es una cadena vacia)
   */
  public function fieldsConcat(Entity $entity, array $visitedTables = null, $alias = ""){
    if(is_null($visitedTables)) $visitedTables = array();
    array_push($visitedTables, $entity->getName());

    if(!empty($alias)){
      $tableIdentification = $alias;
      $aliasParam = $alias . "_";
    } else {
      $tableIdentification = $entity->getAlias();
      $aliasParam = "";
    }

    $this->string .= $this->fieldsConcatMf($entity, $tableIdentification);
    $this->string .= $this->fieldsConcatFk($entity, $visitedTables, $aliasParam);
  }

  protected function fieldsConcatMf(Entity $entity, $tableIdentification){
    $mf = $entity->getFieldsByType(array("pk","nf"));

    foreach ( $mf as $field ) {
      if($field->isMain()){ $this->string .= $tableIdentification . "." . $field->getName() . ", "; }
    }
  }

  protected function fieldsConcatFk(Entity $entity, array $visitedTables, $aliasParam){
    $fk = $entity->getFieldsFkNotReferenced($visitedTables);

    foreach ($fk as $field){
      if($field->isMain()){ $this->string .= $this->fieldsConcat($field->getEntityRef(), $visitedTables, $aliasParam . $field->getAlias()); }
    }
  }


  public function generate(){
    if (!$this->isFeasible()) return "";
    $this->start();
    $this->fieldsConcat($this->getEntity());
    $this->end();

    return $this->string;
  }
  
  protected function start(){
    $this->string .= "
  //***** @override *****
  public function fieldsLabel(){
      return \"CONCAT_WS(', ', ";
  }
	
  protected function end(){
    $this->string = rtrim($this->string);
    $this->string = rtrim($this->string, ", ");
    $this->string .= ") AS label, 
    \";
  }

";
  }
  
}

?>