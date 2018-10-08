<?php

require_once("generate/GenerateEntity.php");


class TypescriptEntity_initJson extends GenerateEntity {

  public function generate() {
    //if(!$this->entity->hasRelations()) return "";

    $this->start();
    $this->body();
    $this->end();

    return $this->string;
  }


  protected function start(){
    $this->string .= "  //Inicializar a traves de json
  public setJson(row: { [index: string]: any } = null): { [index: string]: any } {
    if(!row) return;
    Object.assign(this, row);
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
      }
    }
  }




  protected function end(){
    $this->string .= "  }

";
  }


  protected function date(Field $field){
    $this->string .= "    if (row[\"" . $field->getName() . "\"]) this." . $field->getName() . " = this._date(row[\"" . $field->getName() . "\"]);
";
  }


  protected function timestamp(Field $field){
    $this->string .= "    if (row[\"" . $field->getName() . "\"]) this." . $field->getName() . " = this._timestamp(row[\"" . $field->getName() . "\"]);
";
  }

}
