<?php

require_once("generate/GenerateEntityRecursiveFk.php");

class ClassSql_mappingField extends GenerateEntityRecursiveFk {

  protected function start(){
    $this->string .= "  public function mappingField(\$field){
    if(\$f = \$this->_mappingFieldEntity(\$field)) return \$f;
";
}

  protected function body(Entity $entity, $prefix) {
    $this->string .= "    if(\$f = EntitySql::getInstanceFromString('{$entity->getName()}', '" . $prefix . "')->_mappingFieldEntity(\$field)) return \$f;
";
  }

  protected function end(){
  $this->string .= "    throw new Exception(\"Campo no reconocido \" . \$field);
  }

";
  }



}
