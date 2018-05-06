<?php

require_once("generate/GenerateEntity.php");
require_once("function/settypebool.php");

class EntityDataDefinition_InitForm extends GenerateEntity {


  public function generate() {

    $this->start();
    $this->pkNfFk();
    $this->fk();
    $this->u_();
    $this->end();

    return $this->string;
  }


  protected function start(){
    $this->string .= " initForm(row: { [index: string]: any }, sync: { [index: string]: any } = null): Observable<{ [index: string]: any }> {
    if(!row) row = {};
    let row_: { [index: string]: any } = {};
    let observables = [];

";
  }


  protected function pkNfFk(){
    $fields = $this->getEntity()->getFields();

    foreach($fields as $field){
      switch ( $field->getSubtype() ) {
        case "checkbox": $this->checkbox($field); break;
        case "date": $this->date($field);  break;
        case "float": case "integer": case "cuil": case "dni": $this->number($field); break;
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
        default: $this->defecto($field);
      }
    }
  }

  protected function u_(){
    $fields = $this->getEntity()->getFieldsU_();
    foreach($fields as $field){
      switch ( $field->getSubtype() ) {
        default: $this->fieldU_($field);
      }
    }
  }




  protected function end(){
    $this->string .= "
    if(!observables.length) return of(row_);
    return Observable.forkJoin(observables).map(
      response => { return row_; }
    );
  }

";
  }




  protected function date(Field $field) {
    $default = (!empty($field->getDefault())) ?  "'" . $field->getDefault() . "'" : "null";
    $this->string .= "    var value = (('" . $field->getName() . "' in row) && row['" . $field->getName() . "']) ? row['" . $field->getName() . "'] : " . $default . ";
    var date = this.dd.parser.date(value);
    this.dd.parser.dateFormat(date,'object');

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
    $default = (!empty($field->getDefault())) ?  "\"" . $field->getDefault() . "\"" : "null";
    $this->string .= "    var value = (('" . $field->getName() . "' in row) && row['" . $field->getName() . "']) ? row['" . $field->getName() . "'] : " . $default . ";
    row_[\"" . $field->getName() . "\"] = value;

";
  }



  protected function typeahead(Field $field){
    $this->string .= "
    if(this.dd.isSync('" . $field->getName() . "', sync)) {
      var ob = this.dd.getOrNull('" . $field->getEntityRef()->getName() . "', row['" . $field->getName() . "']).mergeMap(
        rowG => {
          if(!rowG) {
            row_['plan'] = null;
            return of(null);
          }

          return this.dd.initLabel('plan', rowG).map(
            rowI => { row_['plan'] = rowI; }
          );
        }
      )
      observables.push(ob);
    }

";
  }

  //los fields u_ se definen solo como consulta
  protected function fieldU_(Field $field){
    $this->string .= "
    if(this.dd.isSync('" . $field->getAlias("_") . "', sync)) {
      var ob = this.dd.labelGetOrNull('" . $field->getEntity()->getName() . "', row['" . $field->getAlias("_") . "']).map(
        rowR => { row_['" . $field->getAlias("_") . "'] = rowR; }
      );
      observables.push(ob);
    }

";
  }



}
