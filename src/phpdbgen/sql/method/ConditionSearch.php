<?php



class ClassSql_conditionSearch extends GenerateEntityRecursiveFk{


  protected function start(){
    $this->string .= "  public function conditionSearch(\$search = \"\"){
    if(empty(\$search)) return '';
    \$condition = \$this->_conditionSearch(\$search) . \"
";
  }

  protected function end(){
    $pos = strrpos($this->string, " .");
    $this->string = substr_replace($this->string , ";" , $pos, 4);
    $this->string .= "    return \"(\" . \$condition . \")\";
  }

";
  }



  protected function body(Entity $entity, $prefix){
    $this->string .= " OR \" . EntitySql::getInstanceRequire('{$entity->getName()}', '{$prefix}')->_conditionSearch(\$search) . \"
";
  }








}
