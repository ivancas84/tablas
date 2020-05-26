<?php


require_once("GenerateEntityRecursiveFk.php");

class ClassSql_conditionFieldStruct extends GenerateEntityRecursiveFk{



  protected function start(){
    $this->string .= "  protected function conditionFieldStruct(\$field, \$option, \$value) {
    if(\$c = \$this->_conditionFieldStruct(\$field, \$option, \$value)) return \$c;
";
  }


  protected function body(Entity $entity, $prefix){
    $this->string .= "    if(\$c = EntitySql::getInstanceRequire('{$entity->getName()}','{$prefix}')->_conditionFieldStruct(\$field, \$option, \$value)) return \$c;
";
  }

  protected function end(){
    $this->string .= "  }

";
  }







}
