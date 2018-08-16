<?php

require_once("generate/GenerateEntityRecursive.php");

class ClassSql_joinAux extends GenerateEntityRecursive {
  public $fields = [];

  public function generate(){
    if(!$this->getEntity()->hasRelations()) return "";

    $this->start();
    $this->recursive($this->getEntity());
    $this->end();
    return $this->string;
  }


  protected function start(){
    $this->string .= "  public function joinAux(){
    \$join = \$this->_joinAux() . '
';
";
  }

  /**
  * Generar sql distinct fields
  * @param array $table Tabla de la estructura
  * @param string $string Codigo generado hasta el momento
  * @return string Codigo generado
  */
  protected function body(Entity $entity, $prefix){
    $this->string .= "    \$sql = new {$entity->getName("XxYy")}Sql; \$join .= \$sql->_joinAux('{$prefix}') . '
';
";

  }

  protected function end(){
    $pos = strrpos($this->string, ",");
    $this->string = substr_replace($this->string , "" , $pos, 2);
    $this->string .= "    return \$join;
  }

";
  }







}
