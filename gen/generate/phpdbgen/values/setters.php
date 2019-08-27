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
        case "date": $this->dateTime($field, 'Y-m-d'); break;
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
      $this->defecto($field);
    }
  }

  protected function integer(Field $field){
    $this->string .= "  public function set{$field->getName('XxYy')}(\$p) {
    \$p = trim(\$p);
    \$this->{$field->getName('xxYy')} = (is_null(\$p) && \$p !== 0) ? null : intval(\$p);
  }
";
  }

  protected function float(Field $field){
    $this->string .= "  public function set{$field->getName('XxYy')}(\$p) {
      \$p = trim(\$p);
      \$this->{$field->getName('xxYy')} = (is_null(\$p) && \$p !== 0) ? null : floatval(\$p);
  }

";
  }

  protected function boolean(Field $field){
    $this->string .= "  public function set{$field->getName('XxYy')}(\$p) {
    \$p = trim(\$p);
    \$this->{$field->getName('xxYy')} = (is_null(\$p)) ? null : settypebool(\$p);
  }
";
  }


  protected function defecto(Field $field){
    $this->string .= "  public function set{$field->getName('XxYy')}(\$p) {
    \$p = trim(\$p);
    \$this->{$field->getName('xxYy')} = (empty(\$p)) ? null : (string)\$p;
  }

";
  }

  protected function dateTime(Field $field, $format){
    $this->string .= "  public function set{$field->getName('XxYy')}(DateTime \$p = null) {
    \$this->{$field->getName('xxYy')} = \$p;
  }

  public function set{$field->getName('XxYy')}Str(\$p, \$format = \"{$format}\") {
    \$p = SpanishDateTime::createFromFormat(\$format, trim(\$p));
    \$this->{$field->getName('xxYy')} = (empty(\$p)) ? null : \$p;
  }

";
  }
}