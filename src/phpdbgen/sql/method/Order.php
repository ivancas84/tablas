<?php

//NO UTILIZADO, SE DEJA COMO REFERENCIA
require_once("GenerateEntityRecursiveFk.php");

class ClassSql_order extends GenerateEntityRecursiveFk{

  protected function start(){
    $this->string .= "  public function orderBy(array \$order = null) { //Definir ordenamiento
    /**
     * Existe un método genérico pero solo funciona para MySql, este metodo permite dar soporte a los dos motores MySql y Postgres
     * Define ordenamiento, si el field ingresado no es mapeado, entonces no se define ordenamiento pero no genera error
     */
    if(empty(\$order)) return '';

    \$sql = '';

    foreach(\$order as \$key => \$value){
      if(\$field = \$this->_mappingField(\$key)){
        \$sql_ = \$this->_order('{$this->entity->getAlias()}.'.\$key, \$value); //el unico caso para el que debe definirse alias!
        \$sql .= concat(\$sql_, ', ', ' ORDER BY', \$sql);
        continue;
      }
";
  }


  protected function body(Entity $entity, $prefix){
    $this->string .= "      \$cls = new {$entity->getName("XxYy")}Sql;
      if(\$field = \$cls->_mappingField(\$key, '{$prefix}')){
        \$sql_ = \$this->_order(\$key, \$value);
        \$sql .= concat(\$sql_, ', ', ' ORDER BY', \$sql);
        continue;
      }
";
  }

  protected function end(){
    $this->string .= "      throw new Exception(\"No pudo definirse el SQL de ordenamiento {\$field} {\$option} {\$value}\");
    }

    return \$sql;
  }

";
  }







}
