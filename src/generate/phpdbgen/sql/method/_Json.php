<?php


class ClassSql__json extends GenerateEntity {


   public function generate(){
    $this->start();
    $this->pk();
    $this->nf();
    $this->fk();
    $this->end();
    return $this->string;
  }

  protected function start(){
    $this->string .= "  public function _json(array \$row = NULL){
    if(empty(\$row)) return null;
    \$prefix = \$this->prf();
    \$row_ = [];
";
  }

  protected function pk(){
    $field = $this->getEntity()->getPk();
    $this->string .= "    \$row_[\"" . $field->getName(). "\"] = (is_null(\$row[\$prefix . \"" . $field->getName() . "\"])) ? null : (string)\$row[\$prefix . \"" . $field->getName() . "\"]; //la pk se trata como string debido a un comportamiento erratico en angular 2 que al tratarlo como integer resta 1 en el valor
";
  }

  protected function fk(){
    $pkNfFk = $this->getEntity()->getFieldsFk();
    foreach ( $pkNfFk as $field ) {
      $this->string .= "    \$row_[\"" . $field->getName(). "\"] = (is_null(\$row[\$prefix . \"" . $field->getName() . "\"])) ? null : (string)\$row[\$prefix . \"" . $field->getName() . "\"]; //las fk se transforman a string debido a un comportamiento errantico en angular 2 que al tratarlo como integer resta 1 en el valor
";
    }
  }

  protected function nf(){
    $pkNfFk = $this->getEntity()->getFieldsNf();
    foreach ( $pkNfFk as $field ) {
      if ($field->isHidden()) continue;

      switch($field->getDataType()) {
        case "integer": $this->string .= "    \$row_[\"" . $field->getName(). "\"] = (is_null(\$row[\$prefix . \"" . $field->getName() . "\"])) ? null : intval(\$row[\$prefix . \"" . $field->getName() . "\"]);
";
        break;

        case "float": $this->string .= "    \$row_[\"" . $field->getName(). "\"] = (is_null(\$row[\$prefix . \"" . $field->getName() . "\"])) ? null : floatval(\$row[\$prefix . \"" . $field->getName() . "\"]);
";
        break;

        case "boolean": $this->string .= "    \$row_[\"" . $field->getName(). "\"] = (is_null(\$row[\$prefix . \"" . $field->getName() . "\"])) ? null : settypebool(\$row[\$prefix . \"" . $field->getName() . "\"]);
";
        break;

        default: $this->string .= "    \$row_[\"" . $field->getName(). "\"] = (is_null(\$row[\$prefix . \"" . $field->getName() . "\"])) ? null : (string)\$row[\$prefix . \"" . $field->getName() . "\"];
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
