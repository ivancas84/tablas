<?php


require_once("generate/GenerateEntityRecursive.php");

class ClassSql_conditionHistory extends GenerateEntityRecursive{


  protected function start(){
    $this->string .= "  public function conditionHistory(array \$history = []) {
    if(!key_exists('history', \$history)) \$history['history'] = false;
    \$c = \$this->_conditionHistory(\$history);
";
  }


  protected function body(Entity $entity, $prefix){
    $this->string .= "    \$c .= concat(Dba::sql('{$entity->getName()}','{$prefix}')->_conditionHistory(\$history), ' AND');
";
  }

  protected function end(){
    $this->string .= "    return \$c;
  }

";
  }

}
