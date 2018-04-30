<?php

require_once("generate/GenerateEntity.php");


class EntityDataDefinition_ServerFilters extends GenerateEntity {


  public function generate() {
    $this->start();
    $this->pk();
    $this->nf();
    $this->fk();
    $this->end();

    return $this->string;
  }


  protected function start(){
    $this->string .= "  serverFilters(filters: any[]): any[] {
    if(!filters || !filters.length) return [];
    let filters_ = [];

    for(let i = 0; i < filters.length; i++){
";
  }


  protected function nf(){
    $fields = $this->getEntity()->getFieldsNf();
    foreach($fields as $field){
      switch ( $field->getSubtype() ) {
        case "date": $this->date($field); break;
        default: $this->defecto($field);

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

  protected function end(){
    $this->string .= "    }

    return filters_;
  }

";
  }


  protected function pk(){
    $field = $this->entity->getPk();
    $this->string .= "      if((filters[i].field == '" . $field->getName() . "') && (typeof filters[i].value == 'object')){
        var filter = [filters[i].field, filters[i].option, filters[i].value.id];
        filters_.push(filter);
      }
";
  }


  protected function date(Field $field){
    $this->string .= "      if(filters[i].field == '" . $field->getName() . "' && filters[i].value) {
        var value = this.dd.parser.dateFormat(filters[i].value);
        var filter = [filters[i].field, filters[i].option, value];
        filters_.push(filter);
      }
";
  }

  protected function defecto(Field $field){
    $this->string .= "      if(filters[i].field == '" . $field->getName() . "' && filters[i].value) {
        var filter = [filters[i].field, filters[i].option, filters[i].value];
        filters_.push(filter);
      }
";
  }

  protected function typeahead(Field $field){
    $this->string .= "      if((filters[i].field == '" . $field->getName() . "') && (typeof filters[i].value == 'object')){
        var filter = [filters[i].field, filters[i].option, filters[i].value.id];
        filters_.push(filter);
      }
";
  }

}
