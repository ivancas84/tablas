<?php

require_once("generate/GenerateEntity.php");


class EntityDataDefinition_Label extends GenerateEntity {


  public function generate() {
    if(!$this->defineFields()) return "";

    $this->start();
    $this->observables();
    $this->pk();
    $this->nf();
    $this->fk();
    $this->end();
    return $this->string;
  }


  protected function defineFields(){
   $this->fields["pk"] = null;
   $this->fields["nf"] = array();
   $this->fields["fk"] = array();

   $pk = $this->getEntity()->getPk();
   $nf = $this->getEntity()->getFieldsNf();
   $fk = $this->getEntity()->getFieldsFk();

   if($pk->isMain()) $this->fields["pk"] = $pk;
   foreach ($nf as $field){ if($field->isMain()) array_push($this->fields["nf"], $field); }
   foreach ($fk as $field){ if($field->isMain()) array_push($this->fields["fk"], $field); }
   if(!count($this->fields["nf"]) && !count($this->fields["fk"])) return false;
   return true;
 }




  protected function start(){
    $this->string .= "  label (row: { [index: string]: any }): Observable<string> {
    let ret = \"\";
";
  }

  protected function observables(){
    if(count($this->fields["fk"])) $this->string .= "    let obs = [];
";
  }

  protected function pk(){
    $pk = $this->fields["pk"];
    if($pk) $this->defecto($pk);
  }

  protected function nf(){
    $fields = $this->fields["nf"];

    foreach($fields as $field){

      switch ( $field->getSubtype() ) {
        case "date": $this->date($field); break;
        default: $this->defecto($field); break;
      }
    }
  }


  protected function fk(){
    if(!count($this->fields["fk"])) return;
    $this->string .= "
    //CUIDAR DE NO GENERAR RECURSIONES INFINITAS PARA LAS RELACIONES
";
    $fields = $this->fields["fk"];

    foreach($fields as $field){
      switch ( $field->getSubtype() ) {
        case "select": case "typeahead":
          $this->get($field);
        break;
      }
    }
  }

  protected function defecto(Field $field){
    $this->string .= "    if (row[\"" . $field->getName() . "\"]) ret = ret.trim() + \" \" + row[\"" . $field->getName() . "\"];

";
  }

  protected function date(Field $field){
    $this->string .= "    if (row[\"" . $field->getName() . "\"]) ret = ret.trim() + \" \" + this.dd.parser.dateString(row[\"" . $field->getName() . "\"]);

";
  }


  protected function get(Field $field){
    $this->string .= "    if(row." . $field->getName() . ") {
      if('" . $field->getName() . "_' in row && row." . $field->getName() . "_){ ret = ret.trim() + ' ' + row." . $field->getName() . "_; }
      else {
        var ob = this.dd.labelGet(\"" . $field->getEntityRef()->getName() . "\", row." . $field->getName() . ");
        obs.push(ob);
      }
    }

";
  }

  protected function end(){
    if(!count($this->fields["fk"])) $this->string .= "    return of(ret.trim());
  }

";
    else {
      $this->string .= "    if(!obs.length) return of(ret.trim());

    return Observable.forkJoin(obs).map(
      response => {
        for(let i = 0; i < response.length; i++) {
          if(response[i]) ret = ret.trim() + \" \" + response[i];
        }
        return ret.trim();
      }
    );
  }

";
    }
  }




}
