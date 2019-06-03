<?php


class ClassSql__mappingFieldAggregate extends GenerateEntity{

  protected function isFeasible(){
    foreach ($this->getEntity()->getFieldsNf() as $field){
      $d = $field->getDataType();
      if(($d == "integer") 
      || ($d == "float")
      || ($d == "date")
      || ($d == "timestamp")) return true;
    }
    return false;

  }

  public function generate(){
    if(!$this->isFeasible()) return null;
    $this->start();
    $this->main();
    $this->end();
    return $this->string;
  }


  protected function start(){
    $this->string .= "  public function _mappingFieldAggregate(\$field){
    \$t = \$this->getEntity()->getAlias();

    switch (\$field) {
";
  }

  protected function main(){
    foreach ($this->getEntity()->getFieldsNf() as $field){
      switch($field->getDataType()){
        case "float": case "integer":
          $this->string .= "      case 'sum_" . $field->getName() . "': return \"SUM({\$t}.{$field->getName()})\";
      case 'avg_" . $field->getName() . "': return \"AVG({\$t}.{$field->getName()})\";
      case 'min_" . $field->getName() . "': return \"MIN({\$t}.{$field->getName()})\";
      case 'max_" . $field->getName() . "': return \"MAX({\$t}.{$field->getName()})\";
      case 'count_" . $field->getName() . "': return \"COUNT({\$t}.{$field->getName()})\";
";
        break;
        case "date": case "timestamp":
        $this->string .= "      case 'avg_" . $field->getName() . "': return \"AVG({\$t}.{$field->getName()})\";
      case 'min_" . $field->getName() . "': return \"MIN({\$t}.{$field->getName()})\";
      case 'max_" . $field->getName() . "': return \"MAX({\$t}.{$field->getName()})\";
      case 'count_" . $field->getName() . "': return \"COUNT({\$t}.{$field->getName()})\";
";
        break;
      }

    }
  }

  protected function end(){
    $this->string .= "      default: return null;
    }
  }

";
  }


}
