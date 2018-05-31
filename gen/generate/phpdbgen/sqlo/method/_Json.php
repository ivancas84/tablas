<?php


class ClassSqlo__json extends GenerateEntity {


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
  //@override
  public function _json(array \$row, \$prefix = \"\"){
    if(empty(\$row)) return null;
    \$row_ = [];
";
  }

  protected function pk(){
    $field = $this->getEntity()->getPk();
    $this->string .= "    if(isset(\$row[\$prefix . \"" . $field->getName() . "\"])) \$row_[\"" . $field->getName(). "\"] = (is_null(\$row[\$prefix . \"" . $field->getName() . "\"])) ? null : (string)\$row[\$prefix . \"" . $field->getName() . "\"]; //la pk se trata como string debido a un comportamiento erratico en angular 2 que al tratarlo como integer resta 1 en el valor
";
  }

  protected function fk(){
    $pkNfFk = $this->getEntity()->getFieldsFk();
    foreach ( $pkNfFk as $field ) {
      $this->string .= "    if(isset(\$row[\$prefix . \"" . $field->getName() . "\"])) \$row_[\"" . $field->getName(). "\"] = (is_null(\$row[\$prefix . \"" . $field->getName() . "\"])) ? null : (string)\$row[\$prefix . \"" . $field->getName() . "\"]; //las fk se transforman a string debido a un comportamiento errantico en angular 2 que al tratarlo como integer resta 1 en el valor
";
    }
  }

  protected function nf(){
    $pkNfFk = $this->getEntity()->getFieldsNf();
    foreach ( $pkNfFk as $field ) {


      switch($field->getSubtype()) {
        case "integer": $this->string .= "    if(isset(\$row[\$prefix . \"" . $field->getName() . "\"])) \$row_[\"" . $field->getName(). "\"] = (is_null(\$row[\$prefix . \"" . $field->getName() . "\"])) ? null : intval(\$row[\$prefix . \"" . $field->getName() . "\"]);
";
          break;
        case "checkbox": $this->string .= "    if(isset(\$row[\$prefix . \"" . $field->getName() . "\"])) \$row_[\"" . $field->getName(). "\"] = (is_null(\$row[\$prefix . \"" . $field->getName() . "\"])) ? null : settypebool(\$row[\$prefix . \"" . $field->getName() . "\"]);
";
          break;
        default: $this->string .= "    if(isset(\$row[\$prefix . \"" . $field->getName() . "\"])) \$row_[\"" . $field->getName(). "\"] = (is_null(\$row[\$prefix . \"" . $field->getName() . "\"])) ? null : (string)\$row[\$prefix . \"" . $field->getName() . "\"];
";
      }
    }
  }



    protected function end(){
      $this->string .= "    return \$row_;
  }
  ";
    }



}
