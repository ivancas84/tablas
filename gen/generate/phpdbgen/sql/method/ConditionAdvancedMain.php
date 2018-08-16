<?php


require_once("generate/GenerateEntityRecursive.php");

class ClassSql_conditionAdvancedMain extends GenerateEntityRecursive{



  protected function start(){
    $this->string .= "  //@override
  protected function conditionAdvancedMain(\$field, \$option, \$value, \$prefix = '') {
    \$p = (empty(\$prefix)) ?  ''  : \$prefix . '_';

    if(\$c = \$this->_conditionAdvanced(\$field, \$option, \$value, \$prefix)) return \$c;
";
  }


  protected function body(Entity $entity, $prefix){
    $this->string .= "    \$sql = new {$entity->getName("XxYy")}Sql; if(\$c = \$sql->_conditionAdvanced(\$field, \$option, \$value, \$p.'{$prefix}')) return \$c;
";
  }

  protected function end(){
    $this->string .= "    throw new Exception(\"No pudo definirse la condicion avanzada {\$field} {\$option} {\$value}\");
  }

";
  }







}
