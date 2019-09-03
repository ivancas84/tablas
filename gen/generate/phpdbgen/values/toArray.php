<?php


class Values_toArray extends GenerateEntity {


   public function generate(){
    $this->start();
    $this->body();
    $this->end();
    return $this->string;
  }
  
  protected function start(){
    $this->string .= "  public function _toArray(){
    \$row = [];
";
  }



  protected function body(){
    $pkNfFk = $this->getEntity()->getFields();
    foreach ( $pkNfFk as $field ) {
      $this->string .= "    if(\$this->{$field->getName('xxYy')} !== UNDEFINED) \$row[\"" . $field->getName() . "\"] = \$this->{$field->getName('xxYy')}();
";
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
