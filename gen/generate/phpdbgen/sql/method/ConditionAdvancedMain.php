<?php


require_once("generate/GenerateEntityRecursiveFk.php");

class ClassSql_conditionAdvancedMain extends GenerateEntityRecursiveFk{



  protected function start(){
    $this->string .= "  //@override
  protected function conditionAdvancedMain(\$field, \$option, \$value) {
    if(\$c = \$this->_conditionAdvanced(\$field, \$option, \$value)) return \$c;
";
  }


  protected function body(Entity $entity, $prefix){
    $this->string .= "    if(\$c = Dba::sql('{$entity->getName()}','{$prefix}')->_conditionAdvanced(\$field, \$option, \$value)) return \$c;
";
  }

  protected function end(){
    $this->string .= "    throw new Exception(\"No pudo definirse la condicion avanzada {\$field} {\$option} {\$value}\");
  }

";
  }







}
