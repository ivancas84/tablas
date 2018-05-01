<?php

require_once("generate/GenerateEntity.php");


class EntityDataDefinition_InitMain extends GenerateEntity {


  public function generate() {
    $this->start();
    $this->body();
    $this->end();

    return $this->string;
  }


  protected function start(){
    $this->string .= "  initMain(row: { [index: string]: any }): { [index: string]: any } {
    if(!row) return null;
    let row_: { [index: string]: any } = Object.assign({}, row);

";
  }




  protected function body(){
    $fields = $this->getEntity()->getFieldsNf();

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
    $this->string .= "    return row_;
  }

";
  }





  protected function date(Field $field){
    $this->string .= "    if (row[\"" . $field->getName() . "\"]) row_[\"" . $field->getName() . "\"] = this.dd.parser.date(row[\"" . $field->getName() . "\"]);
";
  }


  protected function timestamp(Field $field){
    $this->string .= "    if (row[\"" . $field->getName() . "\"]) row_[\"" . $field->getName() . "\"] = this.dd.parser.timestamp(row[\"" . $field->getName() . "\"]);
  ";
  }
}
