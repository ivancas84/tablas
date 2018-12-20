<?php

require_once("generate/GenerateEntityRecursiveFk.php");

class ClassSql_joinAux extends GenerateEntityRecursiveFk {
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
    return \"
{\$this->_joinAux()}
";
  }

  /**
  * Generar sql distinct fields
  * @param array $table Tabla de la estructura
  * @param string $string Codigo generado hasta el momento
  * @return string Codigo generado
  */
  protected function body(Entity $entity, $prefix){
    $this->string .= "\" . Dba::sql('{$entity->getName()}', '{$prefix}')->_joinAux() . \"
";

  }

  protected function end(){
    //$pos = strrpos($this->string, ".");
    //$this->string = substr_replace($this->string , ";" , $pos, 3);
    $this->string .= "\";
  }

";
  }







}
