<?php


class ClassSqlo__values extends GenerateEntity {


   public function generate(){
    $this->start();
    $this->pk();
    $this->body();
    $this->end();
    return $this->string;
  }

  protected function start(){
    $this->string .= "
  //@override
  public function _values(array \$row){
    if(empty(\$row)) return null;
    \$row_ = [];
";
  }

  protected function pk(){
    $field = $this->getEntity()->getPk();
    $this->string .= "    if(isset(\$row[\"" . $field->getName() . "\"])) \$row_[\"" . $field->getName() . "\"] = (is_null(\$row[\"" . $field->getName() . "\"])) ? null : (string)\$row[\"" . $field->getName() . "\"]; //la pk se trata como string debido a un comportamiento erratico en angular 2 que al tratarlo como integer resta 1 en el valor
";
  }

  protected function body(){
    $pkNfFk = $this->getEntity()->getFieldsByType(["nf", "fk"]);
    foreach ( $pkNfFk as $field ) {


      switch($field->getDataType()){
        case "integer": $this->string .= "    if(isset(\$row[\"" . $field->getName() . "\"])) \$row_[\"" . $field->getName(). "\"] = (is_null(\$row[\"" . $field->getName() . "\"])) ? null : intval(\$row[\"" . $field->getName() . "\"]);
";
        break;
        case "float": $this->string .= "    if(isset(\$row[\"" . $field->getName() . "\"])) \$row_[\"" . $field->getName(). "\"] = (is_null(\$row[\"" . $field->getName() . "\"])) ? null : floatval(\$row[\"" . $field->getName() . "\"]);
        ";
        break;
        case "boolean": $this->string .= "    if(isset(\$row[\"" . $field->getName() . "\"])) \$row_[\"" . $field->getName(). "\"] = (is_null(\$row[\"" . $field->getName() . "\"])) ? null : settypebool(\$row[\"" . $field->getName() . "\"]);
";
        break;

        case "date": $this->date($field); break;
        case "time": $this->time($field); break;
        case "timestamp": $this->timestamp($field); break;


        default: $this->string .= "    if(isset(\$row[\"" . $field->getName() . "\"])) \$row_[\"" . $field->getName(). "\"] = (is_null(\$row[\"" . $field->getName() . "\"])) ? null : (string)\$row[\"" . $field->getName() . "\"];
";
      }
    }
  }

  protected function date(Field $field){
    $this->string .= "    if(isset(\$row[\"" . $field->getName() . "\"])) \$row_[\"" . $field->getName(). "\"] = (is_null(\$row[\"" . $field->getName() . "\"])) ? null : DateTime::createFromFormat('Y-m-d', \$row[\"" . $field->getName() . "\"]);
";
  }

  protected function time(Field $field){
    $this->string .= "    if(isset(\$row[\"" . $field->getName() . "\"])) \$row_[\"" . $field->getName(). "\"] = (is_null(\$row[\"" . $field->getName() . "\"])) ? null : DateTime::createFromFormat('H:i:s', \$row[\"" . $field->getName() . "\"]);
";
  }

  protected function timestamp(Field $field){
    $this->string .= "    if(isset(\$row[\"" . $field->getName() . "\"])) \$row_[\"" . $field->getName(). "\"] = (is_null(\$row[\"" . $field->getName() . "\"])) ? null : DateTime::createFromFormat('Y-m-d H:i:s', \$row[\"" . $field->getName() . "\"]);
";
  }


  protected function end(){
      $this->string .= "    return \$row_;
  }
  ";
    }



}
