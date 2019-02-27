<?php


require_once("generate/GenerateEntityRecursiveFk.php");

class ClassSql_conditionAdvancedMain extends GenerateEntityRecursiveFk{



  protected function start(){
    $this->string .= "  protected function conditionAdvancedMain(\$field, \$option, \$value) {
    if(\$c = \$this->_conditionAdvanced(\$field, \$option, \$value)) return \$c;
";
  }


  protected function body(Entity $entity, $prefix){
    $this->string .= "    if(\$c = EntitySql::getInstanceFromString('{$entity->getName()}','{$prefix}')->_conditionAdvanced(\$field, \$option, \$value)) return \$c;
";
  }

  protected function end(){
    $this->string .= "    throw new Exception(\"No pudo definirse el SQL de la condicion avanzada {\$field} {\$option} {\$value}\");
  }

";
  }







}
