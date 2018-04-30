<?php


class ClassSql__mappingField extends GenerateEntity{

  public function generate(){
    $this->start();
    $this->main();
    $this->end();
    return $this->string;
  }




  protected function start(){
    $this->string .= "
  //@override
  public function _mappingField(\$field, \$prefix=''){
    \$prf = (empty(\$prefix)) ? '' : \$prefix . '_';
    \$prt = (empty(\$prefix)) ? '" . $this->getEntity()->getAlias() . ".' : \$prefix . '.';

    switch (\$field) {
";
  }

  protected function main(){
    foreach ($this->getEntity()->getFields() as $field){
      $this->string .= "      case \$prf.'" . $field->getName() . "': return \$prt.\"" . $field->getName() . "\";
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
