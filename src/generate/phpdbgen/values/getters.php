<?php


class ClassValues_getters extends GenerateEntity {


   public function generate(){
     $this->defecto($this->getEntity()->getPk());
     $pkNfFk = $this->getEntity()->getFieldsByType(["nf", "fk"]);

     foreach ( $pkNfFk as $field ) {

       switch($field->getDataType()){
         case "date": $this->dateTime($field); break;
         case "time": $this->dateTime($field); break;
         case "year": $this->dateTime($field); break;
         case "timestamp": $this->dateTime($field); break;
         case "boolean": $this->boolean($field); break;
         case "string": case "text": $this->text($field); break;
         default: $this->defecto($field);

       }
     }
     return $this->string;

    }


  protected function dateTime(Field $field){
    $this->string .= "  public function {$field->getName('xxYy')}(\$format = null) { return Format::date(\$this->{$field->getName('xxYy')}, \$format); }
";
  }

  protected function boolean(Field $field){
    $this->string .= "  public function {$field->getName('xxYy')}(\$format = null) { return Format::boolean(\$this->{$field->getName('xxYy')}, \$format); }
";
  }

  protected function defecto(Field $field){
    $this->string .= "  public function {$field->getName('xxYy')}() { return \$this->{$field->getName('xxYy')}; }
";
  }

  protected function text(Field $field){
    $this->string .= "  public function {$field->getName('xxYy')}(\$format = null) { return Format::convertCase(\$this->{$field->getName('xxYy')}, \$format); }
";
  }


}
