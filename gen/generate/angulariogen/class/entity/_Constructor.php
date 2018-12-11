<?php

require_once("generate/GenerateEntity.php");
require_once("function/settypebool.php");



class TypescriptEntity_constructor extends GenerateEntity {


  public function generate() {
    if(!$this->hasFields()) return;
    $this->start();
    $this->defecto();
    $this->end();
    return $this->string;
  }

  public function hasFields() {
    foreach($this->getEntity()->getFields() as $field){
      switch ( $field->getDataType() ) {
        case "date": case "timestamp":
          if(!empty($field->getDefault())) return true;
        break;
      }
    }
    return false;
  }

  public function defecto() {
    //if(!$this->entity->hasRelations()) return "";

    $fields = $this->getEntity()->getFields();

    foreach($fields as $field){
      switch ( $field->getDataType() ) {
        case "date": $this->date($field); break;
        case "timestamp": $this->timestamp($field); break;
      }
    }

    return $this->string . "
";
  }


  protected function start(){
    $this->string .= "  constructor(){
";
  }

  protected function end(){
    $this->string .= "  }
";
  }


  protected function date(Field $field){
    if(empty($field->getDefault())) return;

    $this->string .= "    let date = this._stringToDate('{$field->getDefault()}');
    this.{$field->getName()} = this._dateToString(date);
";
  }

  protected function timestamp(Field $field){
    if(empty($field->getDefault())) return;

    $this->string .= "    let date = this._stringToTimestamp('{$field->getDefault()}');
    this.{$field->getName()} = this._timestampToString(date);
";
  }
}
