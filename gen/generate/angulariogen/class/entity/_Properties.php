<?php

require_once("generate/GenerateEntity.php");


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
          $this->property($field, "number"); break;

        case "boolean":
          $this->property($field, "boolean"); break;
        default:
          $this->property($field, "string"); break;
      }
    }


    return $this->string . "
";
  }




  protected function property(Field $field, $type){
    $this->string .= "  public " . $field->getName() . " : {$type};
";
  }



}
