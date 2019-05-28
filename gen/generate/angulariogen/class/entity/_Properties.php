<?php

require_once("generate/GenerateEntity.php");
require_once("function/settypebool.php");



class TypescriptEntity_properties extends GenerateEntity {


  public function generate() {
    //if(!$this->entity->hasRelations()) return "";

    $fields = $this->getEntity()->getFields();

    foreach($fields as $field){
      
      switch ( $field->getDataType() ) {
        /*case "date":
        case "year":
        case "timestamp":
        case "time":
          $this->property($field, "Date"); break;*/

        case "float":
        case "int":
          $this->number($field); break;

        case "boolean":
          $this->boolean($field); break;

        case "date":
        case "timestamp":
          /**
           * Los valores por defecto de date y timestamp deben definirse en el constructor
           */
          $this->date($field); break;

        default:
          $this->string($field); break;
      }
    }


    return $this->string . "
";
  }


  protected function number(Field $field){
    $this->string .= "  public " . $field->getName() . " : number";

    if($field->getDefault()) $this->string .= " = {$field->getDefault()}";
    else $this->string .= " = null";

    $this->string .= ";
";
  }

  protected function boolean(Field $field){
    $this->string .= "  public " . $field->getName() . " : boolean";

    $default = (settypebool($field->getDefault())) ? "true" : "false";

    if($field->getDefault()) $this->string .= " = {$default}";
    else $this->string .= " = null";

    $this->string .= ";
";
  }

  protected function string(Field $field){
    $this->string .= "  public " . $field->getName() . " : string ";

    if($field->getDefault()) $this->string .= " = '{$field->getDefault()}'";
    else $this->string .= " = null";

    $this->string .= ";
";
  }

  protected function date(Field $field){
    $this->string .= "  public " . $field->getName() . " : string = null;
";

  }



}
