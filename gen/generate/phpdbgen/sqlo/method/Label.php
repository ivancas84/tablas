<?php

require_once("generate/GenerateEntityRecursiveFk.php");

class ClassSqlo_label extends GenerateEntityRecursiveFk {
  public $fields = [];

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

  public function isFeasible(){
    $isFeasible = $this->isFeasibleNf($this->getEntity());
    if (!$isFeasible) $isFeasible = $this->isFeasibleFk($this->getEntity());
    return $isFeasible;
  }

  public function generate(){
    if(!$this->isFeasible()) return "";
    $this->start();
    $this->recursive($this->getEntity());
    $this->end();
    return $this->string;
  }

  public function fk(Entity $entity, array $tablesVisited, $prefix){
    $fk = $entity->getFieldsFkNotReferenced($tablesVisited);
    $prf = (empty($prefix)) ? "" : $prefix . "_";
    array_push($tablesVisited, $entity->getName());

    foreach($fk as $field){
      if($field->isMain()) $this->recursive($field->getEntityRef(), $tablesVisited, $prf . $field->getAlias());
    }
  }

  protected function start(){
    $this->string .= "
  public function label(\$row, \$prefix = ''){
    \$p = (empty(\$prefix)) ?  ''  : \$prefix . '_';

    \$fields = [];
";
  }

  /**
  * Generar sql distinct fields
  * @param array $table Tabla de la estructura
  * @param string $string Codigo generado hasta el momento
  * @return string Codigo generado
  */
  protected function body(Entity $entity, $prefix = ""){
    $nf = $entity->getFieldsByType(array("pk","nf"));

    $flag = false;
    foreach ( $nf as $field ) { if($field->isMain()) $flag = true; }
    if($flag) $this->string .= "    array_push(\$fields, \$this->_label(\"{\$p}{$prefix}\");
";
  }

  protected function end(){
    $this->string .= "    return implode(' ', \$fields);
  }

";
  }

}
