<?php

class GenValues_setDefault extends GenerateEntity {
  public function generate(){
    $this->start();
    $this->pk();
    $this->nf();
    $this->fk();
    $this->end();
    return $this->string;
  }

  protected function start(){
    $this->string .= "
  public function setDefault(){
";
  }

  protected function pk(){
    $field = $this->getEntity()->getPk();
    if($field->getDefault()) $this->string .= "    \$this->{$field->getName('xxYy')} = \"" . $field->getDefault() . "\"; //los id siempre deben tratarse como string para evitar problemas de manejo de numero enteros
";
    else $this->string .= "    \$this->{$field->getName('xxYy')} = null;
";
  }

  protected function nf(){
    $pkNfFk = $this->getEntity()->getFieldsNf();
    foreach ( $pkNfFk as $field ) {
      if(!$field->getDefault()){
        $this->string .= "    \$this->{$field->getName('xxYy')} = null;
";
        continue;
      }

      switch($field->getDataType()){
        case "integer": case "float": $this->string .= "    \$this->{$field->getName('xxYy')} = " . $field->getDefault() . ";
";
        break;

        case "string": case "text": $this->string .= "    \$this->{$field->getName('xxYy')} = \"" . $field->getDefault() . "\";
";
        break;

        case "date":
          if($field->getDefault() == "CURRENT_DATE"){
            $this->string .= "    \$this->{$field->getName('xxYy')} = new DateTime();
";
          } else {
            $this->string .= "    \$this->{$field->getName('xxYy')} = DateTime::createFromFormat('Y-m-d', '" . $field->getDefault() . "');
";    
          }
        break;

        case "timestamp":
          if($field->getDefault() == "CURRENT_TIMESTAMP"){
            $this->string .= "    \$this->{$field->getName('xxYy')} = new DateTime();
";
          } else {
            $this->string .= "    \$this->{$field->getName('xxYy')} = DateTime::createFromFormat('Y-m-d H:i:s', '" . $field->getDefault() . "');
";    
          }
        break;

        default: $this->string .= "    \$this->{$field->getName('xxYy')} = " . $field->getDefault() . ";
";
      }
    }
  }

  protected function fk(){
        $pkNfFk = $this->getEntity()->getFieldsFk();
        foreach ( $pkNfFk as $field ) {
                if($field->getDefault()) $this->string .= "    \$this->{$field->getName('xxYy')} = \"" . $field->getDefault() . "\"; //los id siempre deben tratarse como string para evitar problemas de manejo de numero enteros
";
                else $this->string .= "    \$this->{$field->getName('xxYy')} = null;
";
    
        }
  }

  protected function end(){
      $this->string .= "  }

";
    }



}
