<?php


class GenSql__mappingFieldStruct extends GenerateEntity{

  public function generate(){
    $this->start();
    $this->main();
    $this->end();
    return $this->string;
  }


  protected function start(){
    $this->string .= "  public function _mappingFieldStruct(\$field){
    \$p = \$this->prf();
    \$t = \$this->prt();

    switch (\$field) {
";
  }

  protected function main(){
    foreach ($this->getEntity()->getFields() as $field){
      $this->string .= "      case \$p.'" . $field->getName() . "': return \$t.\"." . $field->getName() . "\";
";
    }
  }

  protected function end(){
    $this->string .= "      default: return null;
    }
  }

";
  }


}
