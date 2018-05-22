<?php

require_once("generate/GenerateEntity.php");


class EntityDataDefinition_Server extends GenerateEntity {


  public function generate() {

    $this->start();
    $this->pk();
    $this->nf();
    $this->fk();

    $this->end();

    return $this->string;
  }


  protected function start(){
    $this->string .= "  server(row: { [index: string]: any }): { [index: string]: any } {
    let row_: { [index: string]: any } = {};

";
  }


  protected function pk(){
    $field = $this->getEntity()->getPk();
    $this->string .= "    var value = (('" . $field->getName() . "' in row) && row['" . $field->getName() . "']) ? row['" . $field->getName() . "'] : this.dd.uniqueId();
    row_[\"" . $field->getName() . "\"] = value;

";
  }

  protected function nf(){
    $fields = $this->getEntity()->getFieldsNf();

    foreach($fields as $field){
      switch ( $field->getSubtype() ) {
        // case "checkbox": $this->checkbox($field); break;
        // case "date": $this->date($field);  break;
        // case "float": case "integer": case "cuil": case "dni": $this->number($field); break;
        // case "year": $this->date($field); break;
        // case "timestamp": $this->timestamp($field); break;
        // case "time": $this->time($field); break;
        // case "select_text": $this->defecto($field); break;
        // case "select_int": $this->defecto($field); break;
        default: $this->defecto($field); //name, email
      }
    }
  }

  protected function fk(){
    $fields = $this->getEntity()->getFieldsFk();

    foreach($fields as $field){
      switch ( $field->getSubtype() ) {
        case "typeahead": $this->typeahead($field); break;
        // case "checkbox": $this->checkbox($field); break;
        // case "date": $this->date($field);  break;
        // case "float": case "integer": case "cuil": case "dni": $this->number($field); break;
        // case "year": $this->date($field); break;
        // case "timestamp": $this->timestamp($field); break;
        // case "time": $this->time($field); break;
        // case "select_text": $this->defecto($field); break;
        // case "select_int": $this->defecto($field); break;
        default: $this->defecto($field); //name, email
      }
    }
  }








  protected function end(){
    $this->string .= "    return row_;
  }

";
  }




  protected function date(Field $field) {
    $default = (!empty($field->getDefault())) ?  "'" . $field->getDefault() . "'" : "null";
    $this->string .= "    var value = (('" . $field->getName() . "' in row) && row['" . $field->getName() . "']) ? row['" . $field->getName() . "'] : " . $default . ";
    row_[\"" . $field->getName() . "\"] = this.dd.parser.date(value);

";
  }

  protected function checkbox(Field $field) {
    $default = settypebool($field->getDefault()) ? "true":"false";
    $this->string .= "        var value = (('" . $field->getName() . "' in row) && row['" . $field->getName() . "']) ? row['" . $field->getName() . "'] : " . $default . ";
    row_[\"" . $field->getName() . "\"] = value;

";
  }

  protected function number(Field $field){
    $default = (!empty($field->getDefault())) ?  $field->getDefault() : "null";
    $this->string .= "    var value = (('" . $field->getName() . "' in row) && row['" . $field->getName() . "']) ? row['" . $field->getName() . "'] : " . $default . ";
    row_[\"" . $field->getName() . "\"] = value;

";
  }

  protected function defecto(Field $field) {
    $this->string .= "    if('" . $field->getName() . "' in row){
      value = (row['" . $field->getName() . "'] != '') ? row['" . $field->getName() . "'] : null;
      row_[\"" . $field->getName() . "\"] = value;
    }

";
  }

  protected function typeahead(Field $field) {
    $this->string .= "    if('" . $field->getName() . "' in row){
      value = null;
      if (row['" . $field->getName() . "'] && (typeof row['" . $field->getName() . "'] == 'object')){
        if (('id' in row) && (row['" . $field->getName() . "']['id'] != '')) value = row['" . $field->getName() . "']['id'];
      } else {
        if (row['" . $field->getName() . "'] != '') value = row['" . $field->getName() . "'];
      }
      row_['" . $field->getName() . "'] = value;
    }

";
  }




}
