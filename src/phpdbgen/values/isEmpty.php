<?php


class Values_isEmpty extends GenerateEntity {


   public function generate(){
    $this->start();
    $this->body();
    $this->end();
    return $this->string;
  }
  
  protected function start(){
    $this->string .= "  public function _isEmpty(){
";
  }



  protected function body(){
    $pkNfFk = $this->getEntity()->getFields();
    foreach ( $pkNfFk as $field ) {
      $this->string .= "    if(!Validation::is_empty(\$this->{$field->getName('xxYy')})) return false;
";
    }
  }


  protected function end(){
      $this->string .= "    return true;
  }

";
    }

}
