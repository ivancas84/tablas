<?php

class GenValues_setDefault extends GenerateEntity {
  public function generate(){
    $this->start();
    $this->body();
    $this->end();

    return $this->string;
  }

  protected function start(){
    $this->string .= "
  public function _setDefault(){
";
  }


  public function body(){
    $pkNfFk = $this->getEntity()->getFields();

    foreach ( $pkNfFk as $field ) {      
        $this->string .= "    \$this->set{$field->getName('XxYy')}(DEFAULT_VALUE);
";
    }
  }

  protected function end(){
      $this->string .= "  }

";
    }



}
