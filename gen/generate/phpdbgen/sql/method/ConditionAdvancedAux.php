<?php


require_once("generate/GenerateEntityRecursiveFk.php");

class ClassSql_conditionAdvancedAux extends GenerateEntityRecursiveFk{



  protected function start(){
    $this->string .= "  protected function conditionAdvancedAux(\$field, \$option, \$value) {
    if(\$c = \$this->_conditionAdvancedAux(\$field, \$option, \$value)) return \$c;
";
  }


  protected function body(Entity $entity, $prefix){
    $this->string .= "    if(\$c = EntitySql::getInstanceFromString('{$entity->getName()}','{$prefix}')->_conditionAdvancedAux(\$field, \$option, \$value)) return \$c;
";
  }

  protected function end(){
    $this->string .= "  }

";
  }







}
