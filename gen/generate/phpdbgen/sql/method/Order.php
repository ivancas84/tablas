<?php


require_once("generate/GenerateEntityRecursive.php");

class ClassSql_order extends GenerateEntityRecursive{

  public function generate(){
    $this->start();
    $this->recursive($this->getEntity());
    $this->end();

    return $this->string;
  }

  protected function start(){
    $this->string .= "  //@override
  //Define ordenamiento, si el field ingresado no es mapeado, entonces no se define ordenamiento pero no genera error
  public function order(array \$order) {
    if(empty(\$order)) return '';

    \$sql = '';

    foreach(\$order as \$key => \$value){
      if(\$field = \$this->_mappingField(\$key)){
        \$sql_ = \$this->_order(\$key, \$value);
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
    $this->string .= "      throw new Exception(\"No pudo definirse la condicion avanzada {\$field} {\$option} {\$value}\");
    }

    return \$sql;
  }
";
  }







}
