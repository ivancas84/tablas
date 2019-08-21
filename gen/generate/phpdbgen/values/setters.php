<?php

class ClassValues_setters extends GenerateEntity {

  public function generate(){
    $this->defecto($this->getEntity()->getPk());
    $pkNfFk = $this->getEntity()->getFieldsByType(["nf", "fk"]);

    foreach ( $pkNfFk as $field ) {

      switch($field->getDataType()){
        case "time": $this->dateTime($field, 'H:i'); break;
        case "date": $this->dateTime($field, 'Y-m-d'); break;
        case "timestamp": $this->dateTime($field, 'Y-m-d H:i:s'); break;
        case "integer": $this->integer($field); break;
        case "float": $this->float($field); break;
        case "boolean": $this->boolean($field); break;
        //case "string": case "text": $this->text($field); break;
        default: $this->defecto($field);

      }
    }
    return $this->string;
  }


  protected function integer(Field $field){
    $this->string .= "  public function set{$field->getName('XxYy')}(\$p) {
    if(empty(\$p) && \$p !== 0) return;
    \$this->{$field->getName('xxYy')} = intval(trim(\$p));
  }

";
  }

  protected function float(Field $field){
    $this->string .= "  public function set{$field->getName('XxYy')}(\$p) {
    if(empty(\$p) && \$p !== 0) return;
    \$this->{$field->getName('xxYy')} = floatval(trim(\$p));
  }

";
  }

  protected function boolean(Field $field){
    $this->string .= "  public function set{$field->getName('XxYy')}(\$p) {
    \$this->{$field->getName('xxYy')} = settypebool(trim(\$p));
  }

";
  }


  protected function defecto(Field $field){
    $this->string .= "  public function set{$field->getName('XxYy')}(\$p) {
    if(empty(\$p)) return;
    \$this->{$field->getName('xxYy')} = trim(\$p);
  }

";
  }

  protected function dateTime(Field $field, $format){
    $this->string .= "  public function set{$field->getName('XxYy')}(DateTime \$p) {
    if(empty(\$p)) return;
    \$this->{$field->getName('xxYy')} = \$p;
  }

  public function set{$field->getName('XxYy')}Str(\$p, \$format = \"{$format}\") {
    \$p = SpanishDateTime::createFromFormat(\$format, trim(\$p));
    if(empty(\$p)) return;
    \$this->{$field->getName('xxYy')} = \$p;
  }

";
  }
}