<?php


class ClassSql__conditionHistory extends GenerateEntity{

  protected $field; //field historico

  public function generate(){
    $this->field = $this->entity->getFieldHistory();
    if(empty($this->field)) return;

    return "  public function _conditionHistory(array \$history){
    \$p = \$this->prf();
    if(!key_exists(\"{\$p}history\", \$history)) return;
    \$f = \$this->_mappingField(\$p.'{$this->field->getName()}');
    return (\$history[\"{\$p}history\"]) ? {$this->condition("true")} : {$this->condition("false")};
  }

";
  }

  protected function condition($history){
    switch ( $this->field->getDataType() ) {
      case "boolean": return "\$this->conditionBoolean(\$f, {$history})"; break;
      case "date": return "\$this->conditionDate(\$f, {$history})"; break;
      case "timestamp": return "\$this->conditionTimestamp(\$f, {$history})"; break;
    }
  }
}
