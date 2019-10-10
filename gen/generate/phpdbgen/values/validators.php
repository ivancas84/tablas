<?php


class ClassValues_validators extends GenerateEntity {


  public function generate(){
    $this->success($this->getEntity()->getPk());
    $pkNfFk = $this->getEntity()->getFieldsByType(["nf", "fk"]);

    foreach ( $pkNfFk as $field ) {

      switch($field->getDataType()){
        case "integer": $this->checkMethod($field, "integer"); break;
        case "float": $this->checkMethod($field, "float"); break;
        case "date": case "time": case "year": case "timestamp": $this->checkMethod($field, "date"); break;
        case "boolean": $this->checkMethod($field, "boolean"); break;
        case "string": case "text": $this->checkMethod($field, "string"); break;
        default: $this->defecto($field);
      }
    }
    return $this->string;  
  }

  protected function checkMethod($field, $method){
    $r = ($field->isNotNull()) ? "->required()" : "";
    $this->string .= "  public function check{$field->getName('XxYy')}(\$value) { \$this->_validation->name(\"{$field->getName()}\")->value(\$value)->{$method}(){$r}; }
";
  }

  protected function defecto(Field $field){
    ($field->isNotNull()) ? $this->notNull($field) : $this->success($field);
  }

  protected function success(Field $field){
    $this->string .= "  public function check{$field->getName('XxYy')}(\$value) { return; }
";
  }

  protected function notNull(Field $field){
    $this->string .= "  public function check{$field->getName('XxYy')}(\$value) { \$this->_validation->name(\"{$field->getName()}\")->value(\$value)->required(); }
";
  }

  


}
