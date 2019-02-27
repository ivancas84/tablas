<?php

require_once("generate/GenerateEntityRecursiveFk.php");

class ClassSql_conditionAux extends GenerateEntityRecursiveFk{

  public function generate(){
    if(!$this->getEntity()->hasRelations()) return "";

    $this->start();
    $this->recursive($this->getEntity());
    $this->end();

    return $this->string;
  }

  protected function start(){
    $this->string .= "  public function conditionAux() {
    \$sqlCond = \$this->_conditionAux();
";
  }

  protected function body(Entity $entity, $prefix){
    $this->string .= "    if(\$cond = EntitySql::getInstanceFromString('{$entity->getName()}', '{$prefix}')->_conditionAux()) \$sqlCond .= concat(\$cond, ' AND', '', \$sqlCond);
";
  }

  protected function end(){
    $this->string .= "    return (empty(\$sqlCond)) ? '' : \"({\$sqlCond})\";
  }

";
  }

}
