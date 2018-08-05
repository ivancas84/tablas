<?php

require_once("generate/GenerateEntityRecursive.php");

class ClassSql_fieldsLabelFull extends GenerateEntityRecursive {
  public $fields = [];

  public function generate(){
    if(!$this->getEntity()->hasRelations()) return "";

    $this->start();
    $this->recursive($this->getEntity());
    $this->end();
    return $this->string;
  }


  protected function start(){
    $this->string .= "  public function fieldsLabelFull(){
    \$fields = '';
";
  }

  /**
  * Generar sql distinct fields
  * @param array $table Tabla de la estructura
  * @param string $string Codigo generado hasta el momento
  * @return string Codigo generado
  */
  protected function body(Entity $entity, $prefix){
    $this->string .= "    \$sql = new {$entity->getName("XxYy")}Sql; \$fields .= \$sql->_fieldsLabel('{$prefix}') . ',
';
";
    //$field = "{$entity->getName("XxYy")}Sql::_fields('{$prefix}')";
    //array_push($this->fields, $field);
  }

  protected function end(){
    $pos = strrpos($this->string, ",");
    $this->string = substr_replace($this->string , "" , $pos, 2);
    $this->string .= "    return \$fields;
  }

";
  }







}
