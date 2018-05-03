<?php

require_once("generate/GenerateEntityRecursive.php");

class ClassSql_fieldsFull extends GenerateEntityRecursive {
  public $fields = [];

  public function generate(){
    if(!$this->getEntity()->hasRelations()) return "";

    $this->start();
    $this->recursive($this->getEntity());
    $this->end();
    return $this->string;
  }


  protected function start(){
    $this->string .= "  public function fieldsFull(){
    \$fields = \$this->_fields();
";
  }

  /**
  * Generar sql distinct fields
  * @param array $table Tabla de la estructura
  * @param string $string Codigo generado hasta el momento
  * @return string Codigo generado
  */
  protected function body(Entity $entity, $prefix){
    $this->string .= "    \$sql = new {$entity->getName("XxYy")}Sql; \$fields .= \$sql->_fields('{$prefix}');
";
    //$field = "{$entity->getName("XxYy")}Sql::_fields('{$prefix}')";
    //array_push($this->fields, $field);
  }

  protected function end(){
    $this->string .= "    return \$fields;
  }

";
  }







}
