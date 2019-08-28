<?php


class Values_toArray extends GenerateEntity {


   public function generate(){
    $this->start();
    $this->pk();
    $this->body();
    $this->end();
    return $this->string;
  }
  
  protected function start(){
    $this->string .= "  public function toArray(){
    \$row = [];
";
  }

  protected function pk(){
    $field = $this->getEntity()->getPk();
    $this->string .= "    if(\$this->{$field->getName('xxYy')} !== UNDEFINED) \$row[\"{$field->getName()}\"] = \$this->{$field->getName('xxYy')};
";
  }

  protected function body(){
    $pkNfFk = $this->getEntity()->getFieldsByType(["nf", "fk"]);
    foreach ( $pkNfFk as $field ) {


      switch($field->getDataType()){
        case "date": $this->datetime($field, "Y-m-d"); break;
        case "time": $this->datetime($field, "H:i"); break;
        case "timestamp": $this->datetime($field, "Y-m-d H:i:s"); break;
        case "year": $this->datetime($field, "Y"); break;
        case "boolean": $this->boolean($field); break;
        default: $this->defecto($field);
      }
    }
  }


  protected function end(){
      $this->string .= "    return \$row;
  }

";
    }


    protected function datetime($field, $format){
      $this->string .= "    if(\$this->{$field->getName('xxYy')} !== UNDEFINED) {
        if(empty(\$this->{$field->getName('xxYy')})) \$row[\"" . $field->getName() . "\"] = \$this->{$field->getName('xxYy')};
        else \$row[\"" . $field->getName() . "\"] = \$this->{$field->getName('xxYy')}->format('{$format}');
      }
";
    }

    protected function boolean($field){
      $this->string .= "    if(\$this->{$field->getName('xxYy')} !== UNDEFINED) \$row[\"" . $field->getName() . "\"] = (\$this->{$field->getName('xxYy')}) ? true : false;        
";
    }

    protected function defecto($field){
      $this->string .= "    if(\$this->{$field->getName('xxYy')} !== UNDEFINED) \$row[\"" . $field->getName() . "\"] = \$this->{$field->getName('xxYy')};
";
    }


}
