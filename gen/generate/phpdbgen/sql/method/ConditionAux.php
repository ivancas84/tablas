<?php

require_once("generate/GenerateEntityRecursive.php");

class ClassSql_conditionAux extends GenerateEntityRecursive{

  public function generate(){
    if(!$this->getEntity()->hasRelations()) return "";

    $this->start();
    $this->recursive($this->getEntity());
    $this->end();

    return $this->string;
  }

  protected function start(){
    $this->string .= "  //@override
  public function conditionAux() {
    \$sqlCond = \$this->_conditionAux();
";
  }

  protected function body(Entity $entity, $prefix){
    $this->string .= "    \$sql = new {$entity->getName("XxYy")}Sql; \$cond = \$sql->_conditionAux('{$prefix}');
    \$sqlCond .= concat(\$cond, ' AND', '', \$sqlCond);

";
  }

  protected function end(){
    $this->string .= "    return (empty(\$sqlCond)) ? '' : \"({\$sqlCond})\";
  }
";
  }

}
