<?php


class ClassSql__conditionAdvanced extends GenerateEntity{


  public function generate(){
    $this->start();
    $this->condition($this->getEntity(), $this->getEntity()->getAlias(), "");
    $this->end();

    return $this->string;
  }

  protected function start(){
    $this->string .= "  //@override
  public function _conditionAdvanced(\$field, \$option, \$value, \$prefix = ''){
    \$t = (empty(\$prefix)) ?  '" . $this->getEntity()->getAlias() . "'  : \$prefix;
    \$p = (empty(\$prefix)) ?  ''  : \$prefix . '_';

    switch (\$field){
";
  }

  protected function condition(Entity $entity){
    foreach ( $entity->getFields() as $field) {
      switch ( $field->getDataType() ) {

        case "string":
        case "text": $this->string($field->getName()); break;

        case "integer":
        case "float": $this->number($field->getName()); break;

        case "boolean": $this->boolean($field->getName()); break;

        case "date": $this->date($field->getName()); break;
      }
    }


  }




  protected function string($fieldName){
    $this->string .= "       case \"{\$p}" . $fieldName . "\": return \$this->conditionText(\"{\$t}." . $fieldName . "\", \$value, \$option);
" ;

  }


  protected function number($fieldName){
    $this->string .= "      case \"{\$p}" . $fieldName . "\": return \$this->conditionNumber(\"{\$t}." . $fieldName . "\", \$value, \$option);
" ;
	}

  protected function date($fieldName){
    $this->string .= "      case \"{\$p}" . $fieldName . "\": return \$this->conditionDate(\"{\$t}." . $fieldName . "\", \$value, \$option);
" ;
  }

  protected function boolean($fieldName){
    $this->string .= "      case \"{\$p}" . $fieldName . "\": return \$this->conditionBoolean(\"{\$t}." . $fieldName . "\", \$value);
" ;
  }


  protected function end(){
    $this->string .= "    }
  }

";
  }






}
