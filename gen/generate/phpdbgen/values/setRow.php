<?php


class ClassValues_setRow extends GenerateEntity {


   public function generate(){
    $this->start();
    $this->pk();
    $this->body();
    $this->end();
    return $this->string;
  }

  protected function start(){
    $this->string .= "
  public function setRow(array \$row = NULL){
    if(empty(\$row)) return;
";
  }

  protected function pk(){
    $field = $this->getEntity()->getPk();
    $this->string .= "    if(isset(\$row[\"" . $field->getName() . "\"])) \$this->{$field->getName('xxYy')} = (is_null(\$row[\"" . $field->getName() . "\"])) ? null : (string)\$row[\"" . $field->getName() . "\"]; //la pk se trata como string debido a un comportamiento erratico en angular 2 que al tratarlo como integer resta 1 en el valor
";
  }

  protected function body(){
    $pkNfFk = $this->getEntity()->getFieldsByType(["nf", "fk"]);
    foreach ( $pkNfFk as $field ) {


      switch($field->getDataType()){
        case "integer": $this->string .= "    if(isset(\$row[\"" . $field->getName() . "\"])) \$this->{$field->getName('xxYy')} = (is_null(\$row[\"" . $field->getName() . "\"])) ? null : intval(\$row[\"" . $field->getName() . "\"]);
";
        break;

        case "float": $this->string .= "    if(isset(\$row[\"" . $field->getName() . "\"])) \$this->{$field->getName('xxYy')} = (is_null(\$row[\"" . $field->getName() . "\"])) ? null : floatval(\$row[\"" . $field->getName() . "\"]);
";
        break;

        case "boolean": $this->string .= "    if(isset(\$row[\"" . $field->getName() . "\"])) \$this->{$field->getName('xxYy')} = (is_null(\$row[\"" . $field->getName() . "\"])) ? null : settypebool(\$row[\"" . $field->getName() . "\"]);
";
        break;

        case "date": $this->string .= "    if(isset(\$row[\"" . $field->getName() . "\"])) \$this->{$field->getName('xxYy')} = (is_null(\$row[\"" . $field->getName() . "\"])) ? null : DateTime::createFromFormat('Y-m-d', \$row[\"" . $field->getName() . "\"]);
";
        break;

        case "time": $this->string .= "    if(isset(\$row[\"" . $field->getName() . "\"])) \$this->{$field->getName('xxYy')} = (is_null(\$row[\"" . $field->getName() . "\"])) ? null : DateTime::createFromFormat('H:i:s', \$row[\"" . $field->getName() . "\"]);
";
        break;

        case "timestamp": $this->string .= "    if(isset(\$row[\"" . $field->getName() . "\"])) \$this->{$field->getName('xxYy')} = (is_null(\$row[\"" . $field->getName() . "\"])) ? null : DateTime::createFromFormat('Y-m-d H:i:s', \$row[\"" . $field->getName() . "\"]);
";
        break;

        default: $this->string .= "    if(isset(\$row[\"" . $field->getName() . "\"])) \$this->{$field->getName('xxYy')} = (is_null(\$row[\"" . $field->getName() . "\"])) ? null : (string)\$row[\"" . $field->getName() . "\"];
";
      }
    }
  }


  protected function end(){
      $this->string .= "  }

";
    }



}
