<?php


class ClassValues_getters extends GenerateEntity {


   public function generate(){
     $this->defecto($this->getEntity()->getPk());
     $pkNfFk = $this->getEntity()->getFieldsByType(["nf", "fk"]);

     foreach ( $pkNfFk as $field ) {

       switch($field->getDataType()){
         case "date": $this->dateTime($field, 'd/m/Y'); break;
         case "time": $this->dateTime($field, 'h:i'); break;
         case "timestamp": $this->dateTime($field, 'd/m/Y h:i'); break;
         case "boolean": $this->boolean($field); break;
         default: $this->defecto($field);

       }
     }
     return $this->string;

    }


  protected function dateTime(Field $field, $format){
    $this->string .= "  public function {$field->getName('xxYy')}(\$format = '{$format}') { return \$this->formatDate(\$this->{$field->getName('xxYy')}, \$format); }
";
  }

  protected function boolean(Field $field){
    $this->string .= "  public function {$field->getName('xxYy')}() { return (\$this->{$field->getName('xxYy')}) ? 'Sí' : 'No'; }
";
  }

  protected function defecto(Field $field){
    $this->string .= "  public function {$field->getName('xxYy')}() { return \$this->{$field->getName('xxYy')}; }
";
  }




}
