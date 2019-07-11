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
    $this->string .= "
  public function toArray(){
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
        case "date": $this->string .= "    if(\$this->{$field->getName('xxYy')} !== UNDEFINED) {
      if(empty(\$this->{$field->getName('xxYy')})) \$row[\"" . $field->getName() . "\"] = \$this->{$field->getName('xxYy')};
      else \$row[\"" . $field->getName() . "\"] = \$this->{$field->getName('xxYy')}->format('Y-m-d');
    }
";
        break;

        case "time": $this->string .= "    if(\$this->{$field->getName('xxYy')} !== UNDEFINED) {
      if(empty(\$this->{$field->getName('xxYy')})) \$row[\"" . $field->getName() . "\"] = \$this->{$field->getName('xxYy')};
      else \$row[\"" . $field->getName() . "\"] = \$this->{$field->getName('xxYy')}->format('H:i:s');
    }
";
        break;

        case "timestamp": $this->string .= "    if(\$this->{$field->getName('xxYy')} !== UNDEFINED) {
      if(empty(\$this->{$field->getName('xxYy')})) \$row[\"" . $field->getName() . "\"] = \$this->{$field->getName('xxYy')};
      else \$row[\"" . $field->getName() . "\"] = \$this->{$field->getName('xxYy')}->format('Y-m-d H:i:s');
    }
";
        break;

        case "boolean": $this->string .= "    if(\$this->{$field->getName('xxYy')} !== UNDEFINED) \$row[\"" . $field->getName() . "\"] = (\$this->{$field->getName('xxYy')}) ? \"true\" : \"false\";        
";
        break;

        default: $this->string .= "    if(\$this->{$field->getName('xxYy')} !== UNDEFINED) \$row[\"" . $field->getName() . "\"] = \$this->{$field->getName('xxYy')};
";
      }
    }
  }


  protected function end(){
      $this->string .= "    return \$row;
  }

";
    }



}
