<?php


class ClassSql__conditionFieldStruct extends GenerateEntity{


  public function generate(){
    $this->start();
    $this->condition($this->getEntity(), $this->getEntity()->getAlias(), "");
    $this->end();

    return $this->string;
  }

  protected function start(){
    $this->string .= "  public function _conditionFieldStruct(\$field, \$option, \$value){
    \$p = \$this->prf();

    \$f = \$this->_mappingField(\$field);
    switch (\$field){
";
  }

  protected function condition(Entity $entity){
    foreach ( $entity->getFields() as $field) {
      switch ( $field->getDataType() ) {

        case "string":
        case "text":
        case "year": $this->string($field->getName()); break;

        case "integer":
        case "float": $this->number($field->getName()); break;

        case "boolean": $this->boolean($field->getName()); break;

        case "date": $this->date($field->getName()); break;
        case "time": $this->time($field->getName()); break;

        case "timestamp": $this->timestamp($field->getName()); break;

      }
    }
  }

  protected function string($fieldName) {
    $this->string .= "      case \"{\$p}" . $fieldName . "\": return \$this->format->conditionText(\$f, \$value, \$option);
" ;
  }

  protected function number($fieldName) {
    $this->string .= "      case \"{\$p}" . $fieldName . "\": return \$this->format->conditionNumber(\$f, \$value, \$option);
" ;
	}

  protected function date($fieldName) {
    $this->string .= "      case \"{\$p}" . $fieldName . "\": return \$this->format->conditionDate(\$f, \$value, \$option);
" ;
  }

  protected function timestamp($fieldName) {
    $this->string .= "      case \"{\$p}" . $fieldName . "\": return \$this->format->conditionTimestamp(\$f, \$value, \$option);
" ;
  }

  protected function time($fieldName) {
    $this->string .= "      case \"{\$p}" . $fieldName . "\": return \$this->format->conditionTime(\$f, \$value, \$option);
" ;
  }
  protected function boolean($fieldName) {
    $this->string .= "      case \"{\$p}" . $fieldName . "\": return \$this->format->conditionBoolean(\$f, \$value);
" ;
  }

  protected function end() {
    $this->string .= "      default: return parent::_conditionFieldStruct(\$field, \$option, \$value);
    }
  }

";
  }






}
