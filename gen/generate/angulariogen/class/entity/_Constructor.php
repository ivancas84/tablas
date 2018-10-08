<?php

require_once("generate/GenerateEntity.php");


class TypescriptEntity_constructor extends GenerateEntity {


  public function generate() {
    //if(!$this->entity->hasRelations()) return "";

    $this->start();
    $this->body();
    $this->end();

    return $this->string;
  }


  protected function start(){
    $this->string .= "  constructor(row?: { [index: string]: any }) {
    if(!row) return;

";
  }

  protected function body(){
    $fields = $this->getEntity()->getFields();

    foreach($fields as $field){
      switch ( $field->getSubtype() ) {
        case "date":
        case "year":
          $this->date($field);  break;

        case "timestamp":
        case "time":
          $this->timestamp($field); break;

        default:
            $this->defecto($field); break;
      }
    }
  }


  protected function end(){
    $this->string .= "  }

";
  }






  protected function date(Field $field){
    $this->string .= "    if (row[\"" . $field->getName() . "\"]) this.{$field->getName('xxYy')} = this._date(row[\"" . $field->getName() . "\"]);
";
  }

  protected function defecto(Field $field){
    $this->string .= "    if (row[\"" . $field->getName() . "\"]) this.{$field->getName('xxYy')} = row[\"" . $field->getName() . "\"];
";
  }


  protected function timestamp(Field $field){
    $this->string .= "    if (row[\"" . $field->getName() . "\"]) this.{$field->getName('xxYy')} = this._timestamp(row[\"" . $field->getName() . "\"]);
";
  }


}
