<?php


class GenSql__mappingField extends GenerateEntity{

  public function generate(){
    $this->start();
    $this->struct();
    $this->aggregatePk();
    $this->aggregateNf();
    $this->aggregateFk();
    $this->end();
    return $this->string;
  }


  protected function start(){
    $this->string .= "  public function _mappingField(\$field){
    \$p = \$this->prf();
    \$t = \$this->prt();

    if(\$f = \$this->_mappingFieldMain(\$field)) return \$f;
    switch (\$field) {
";
  }

  protected function struct(){
    foreach ($this->getEntity()->getFields() as $field){
      $this->string .= "      case \$p.'" . $field->getName() . "': return \$t.\"." . $field->getName() . "\";
";
    }
  }

  protected function aggregatePk(){
    $this->string .= "
      case \$p.'min_id': return \"MIN({\$t}.id)\";
      case \$p.'max_id': return \"MAX({\$t}.id)\";
      case \$p.'count_id': return \"COUNT({\$t}.id)\";

";
  }

  protected function aggregateNf(){
    foreach ($this->getEntity()->getFieldsNf() as $field){
      switch($field->getDataType()){
        case "float": case "integer":
          $this->string .= "      case \$p.'sum_" . $field->getName() . "': return \"SUM({\$t}.{$field->getName()})\";
      case \$p.'avg_" . $field->getName() . "': return \"AVG({\$t}.{$field->getName()})\";
      case \$p.'min_" . $field->getName() . "': return \"MIN({\$t}.{$field->getName()})\";
      case \$p.'max_" . $field->getName() . "': return \"MAX({\$t}.{$field->getName()})\";
      case \$p.'count_" . $field->getName() . "': return \"COUNT({\$t}.{$field->getName()})\";

";
        break;
        case "date": case "timestamp":
          $this->string .= "      case \$p.'avg_" . $field->getName() . "': return \"AVG({\$t}.{$field->getName()})\";
      case \$p.'min_" . $field->getName() . "': return \"MIN({\$t}.{$field->getName()})\";
      case \$p.'max_" . $field->getName() . "': return \"MAX({\$t}.{$field->getName()})\";
      case \$p.'count_" . $field->getName() . "': return \"COUNT({\$t}.{$field->getName()})\";

";
        break;
        default:
        $this->string .= "      case \$p.'min_" . $field->getName() . "': return \"MIN({\$t}.{$field->getName()})\";
      case \$p.'max_" . $field->getName() . "': return \"MAX({\$t}.{$field->getName()})\";
      case \$p.'count_" . $field->getName() . "': return \"COUNT({\$t}.{$field->getName()})\";

";
        break;
      }

    }
  }

  protected function aggregateFk(){
    foreach ($this->getEntity()->getFieldsFk() as $field){
      $this->string .= "      case \$p.'min_" . $field->getName() . "': return \"MIN({\$t}.{$field->getName()})\";
      case \$p.'max_" . $field->getName() . "': return \"MAX({\$t}.{$field->getName()})\";
      case \$p.'count_" . $field->getName() . "': return \"COUNT({\$t}.{$field->getName()})\";

";

    }
  }

  protected function end(){
    $this->string .= "      default: return null;
    }
  }

";
  }


}
