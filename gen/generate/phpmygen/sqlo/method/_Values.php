<?php


class ClassSqlo__values extends GenerateEntity {


   public function generate(){
    $this->start();
    $this->pk();
    $this->build();
    $this->end();
    return $this->string;
  }

  protected function start(){
    $this->string .= "
  //@override
  public static function _values(array \$row, \$prefix = \"\"){
    if(empty(\$row)) return null;
";
  }

  protected function pk(){
    $field = $this->getEntity()->getPk();
    $this->string .= "    if(isset(\$row[\$prefix . \"" . $field->getName() . "\"])) \$row_[\"" . $field->getName() . "\"] = (is_null(\$row[\$prefix . \"" . $field->getName() . "\"])) ? null : (string)\$row[\$prefix . \"" . $field->getName() . "\"]; //la pk se trata como string debido a un comportamiento erratico en angular 2 que al tratarlo como integer resta 1 en el valor
";
  }

  protected function build(){
    $pkNfFk = $this->getEntity()->getFieldsByType(["nf", "fk"]);
    foreach ( $pkNfFk as $field ) {


      switch($field->getDataType()){
        case "integer": $this->string .= "    if(isset(\$row[\$prefix . \"" . $field->getName() . "\"])) \$row_[\"" . $field->getName(). "\"] = (is_null(\$row[\$prefix . \"" . $field->getName() . "\"])) ? null : intval(\$row[\$prefix . \"" . $field->getName() . "\"]);
";
          break;
        case "boolean": $this->string .= "    if(isset(\$row[\$prefix . \"" . $field->getName() . "\"])) \$row_[\"" . $field->getName(). "\"] = (is_null(\$row[\$prefix . \"" . $field->getName() . "\"])) ? null : settypebool(\$row[\$prefix . \"" . $field->getName() . "\"]);
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
