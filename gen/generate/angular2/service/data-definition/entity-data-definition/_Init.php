<?php

require_once("generate/GenerateEntity.php");


class EntityDataDefinition_Init extends GenerateEntity {


  public function generate() {
    //if(!$this->entity->hasRelations()) return "";

    $this->start();
    $this->body();
    $this->fk();
    $this->u_();
    $this->end();

    return $this->string;
  }


  protected function start(){
    $this->string .= "  init(row: { [index: string]: any }, sync: { [index: string]: any } = null): Observable<{ [index: string]: any }> {
    if(!row) return null;
    let row_: { [index: string]: any } = Object.assign({}, row);

    let observables = [];

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


  protected function fk(){
    $fields = $this->getEntity()->getFieldsFk();
    foreach($fields as $field){
      switch ( $field->getSubtype() ) {
        default: $this->labelGetOrNull($field);
      }
    }
  }

  protected function u_(){
    $fields = $this->getEntity()->getFieldsU_();
    foreach($fields as $field){
      switch ( $field->getSubtype() ) {
        default: $this->labelGetOrNullU_($field);
      }
    }
  }




  protected function end(){
    $this->string .= "    if(!observables.length) return of(row_);

    return Observable.forkJoin(observables).map(
      response => { return row_; }
    );
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

  protected function labelGetOrNull(Field $field){
    $this->string .= "    if(this.dd.isSync('" . $field->getName() . "', sync)) {
      var ob = this.dd.labelGetOrNull(\"" . $field->getEntityRef()->getName() . "\", row[\"" . $field->getName() . "\"]).map(
        rowR => { row_[\"" . $field->getName() ."_\"] = rowR; }
      );
      observables.push(ob);
    }
";
  }

  protected function labelGetOrNullU_(Field $field){
    $this->string .= "    if(this.dd.isSync('" . $field->getName() . "', sync)) {
      var ob = this.dd.labelGetOrNull(\"" . $field->getEntity()->getName() . "\", row[\"" . $field->getAlias("_") . "\"]).map(
        rowR => { row_[\"" . $field->getAlias("_") ."_\"] = rowR; }
      );
      observables.push(ob);
    }
";
  }
}
