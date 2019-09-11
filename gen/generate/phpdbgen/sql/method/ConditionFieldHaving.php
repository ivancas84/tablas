<?php


require_once("generate/GenerateEntityRecursiveFk.php");

class Sql_conditionFieldHaving extends GenerateEntityRecursiveFk{



  protected function start(){
    $this->string .= "  protected function conditionFieldHaving(\$field, \$option, \$value) {
    if(\$c = \$this->_conditionFieldHaving(\$field, \$option, \$value)) return \$c;
";
  }


  protected function body(Entity $entity, $prefix){
    $this->string .= "    if(\$c = EntitySql::getInstanceRequire('{$entity->getName()}','{$prefix}')->_conditionFieldHaving(\$field, \$option, \$value)) return \$c;
";
  }

  protected function end(){
    $this->string .= "  }

";
  }







}
