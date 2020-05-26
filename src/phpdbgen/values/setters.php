<?php

class ClassValues_setters extends GenerateEntity {

  public function generate(){
    $this->pk();
    $this->nf();
    $this->fk();
    return $this->string;
  }

  public function pk() {
    $this->defecto($this->getEntity()->getPk());
  }
  
  public function nf(){
    $pkNfFk = $this->getEntity()->getFieldsNf();

    foreach ( $pkNfFk as $field ) {

      switch($field->getDataType()){
        case "year": $this->dateTime($field, 'Y'); break;
        case "time": $this->dateTime($field, 'H:i:s'); break;
        case "date": $this->date($field); break;
        case "timestamp": $this->dateTime($field, 'Y-m-d H:i:s'); break;
        case "integer": $this->integer($field); break;
        case "float": $this->float($field); break;
        case "boolean": $this->boolean($field); break;
        //case "string": case "text": $this->text($field); break;
        default: $this->defecto($field);

      }
    }
  }

  public function fk(){
    $pkNfFk = $this->getEntity()->getFieldsFk();

    foreach ( $pkNfFk as $field ) {
      switch($field->getDataType()){
        case "integer": $this->integer($field); break;
        default: $this->defecto($field); break;
      }
    }
  }

  protected function integer(Field $field){
    $default = ($field->getDefault()) ? $field->getDefault() : "null";
    
    $this->string .= "  public function set{$field->getName('XxYy')}(\$p) {
    if (\$p == DEFAULT_VALUE) \$p = " . $default . ";
    \$p = (is_null(\$p)) ? null : intval(trim(\$p));
    \$check = \$this->check{$field->getName('XxYy')}(\$p); 
    if(\$check) \$this->{$field->getName('xxYy')} = \$p;
    return \$check;
  }

";
  }

  protected function float(Field $field){
    $default = ($field->getDefault()) ? $field->getDefault() : "null";

    $this->string .= "  public function set{$field->getName('XxYy')}(\$p) {
    if (\$p == DEFAULT_VALUE) \$p = " . $default . ";
    \$p = (is_null(\$p)) ? null : floatval(trim(\$p));
    \$check = \$this->check{$field->getName('XxYy')}(\$p); 
    if(\$check) \$this->{$field->getName('xxYy')} = \$p;
    return \$check;
  }

";
  }

  protected function boolean(Field $field){
    $default = ($field->getDefault()) ? $field->getDefault() : "null";

    $this->string .= "  public function set{$field->getName('XxYy')}(\$p) {
    if (\$p == DEFAULT_VALUE) \$p = " . $default . ";
    \$p = (is_null(\$p)) ? null : settypebool(trim(\$p));
    \$check = \$this->check{$field->getName('XxYy')}(\$p); 
    if(\$check) \$this->{$field->getName('xxYy')} = \$p;
    return \$check;
  }

";
  }


  protected function defecto(Field $field){
    $default = ($field->getDefault()) ? "'" . $field->getDefault() . "'" : "null";

    $this->string .= "  public function set{$field->getName('XxYy')}(\$p) {
    \$p = (\$p == DEFAULT_VALUE) ? " . $default . " : trim(\$p);
    \$p = (is_null(\$p)) ? null : (string)\$p;
    \$check = \$this->check{$field->getName('XxYy')}(\$p); 
    if(\$check) \$this->{$field->getName('xxYy')} = \$p;
    return \$check;
  }

";
  }

  protected function dateTime(Field $field, $format){
    if(strpos(strtolower($field->getDefault()), "current") !== false){
      $default = "date('{$format}')";
    } else {
      $default = ($field->getDefault()) ? "'" . $field->getDefault() . "'" : "null";
    }

    $this->string .= "  public function _set{$field->getName('XxYy')}(DateTime \$p = null) {
      \$check = \$this->check{$field->getName('XxYy')}(\$p); 
      if(\$check) \$this->{$field->getName('xxYy')} = \$p;  
      return \$check;
  }

  public function set{$field->getName('XxYy')}(\$p, \$format = \"{$format}\") {
    \$p = (\$p == DEFAULT_VALUE) ? " . $default . " : trim(\$p);
    if(!is_null(\$p)) \$p = SpanishDateTime::createFromFormat(\$format, \$p);    
    \$check = \$this->check{$field->getName('XxYy')}(\$p); 
    if(\$check) \$this->{$field->getName('xxYy')} = \$p;  
    return \$check;
  }

";
  }

  protected function date(Field $field){
    if(strpos(strtolower($field->getDefault()), "current")  !== false){
      $default = "date('{$format}')";
    } else {
      $default = ($field->getDefault()) ? "'" . $field->getDefault() . "'" : "null";
    }

    $this->string .= "  public function _set{$field->getName('XxYy')}(DateTime \$p = null) {
      \$check = \$this->check{$field->getName('XxYy')}(\$p); 
      if(\$check) \$this->{$field->getName('xxYy')} = \$p;  
      return \$check;      
  }

  public function set{$field->getName('XxYy')}(\$p, \$format = UNDEFINED) {
    \$p = (\$p == DEFAULT_VALUE) ? " . $default . " : trim(\$p);
    if(!is_null(\$p)) \$p = (\$format == UNDEFINED) ? SpanishDateTime::createFromDate(\$p) : SpanishDateTime::createFromFormat(\$format, \$p);    
    \$check = \$this->check{$field->getName('XxYy')}(\$p); 
    if(\$check) \$this->{$field->getName('xxYy')} = \$p;  
    return \$check;
  }

";
  }
}