<?php


require_once("generate/GenerateEntityRecursiveFk.php");

class ClassSql_conditionField extends GenerateEntityRecursiveFk{



  protected function start(){
    $this->string .= "  protected function conditionField(\$field, \$option, \$value) {
    if(\$c = \$this->_conditionField(\$field, \$option, \$value)) return \$c;
";
  }


  protected function body(Entity $entity, $prefix){
    $this->string .= "    if(\$c = EntitySql::getInstanceFromString('{$entity->getName()}','{$prefix}')->_conditionField(\$field, \$option, \$value)) return \$c;
";
  }

  protected function end(){
    $this->string .= "  }

";
  }







}
