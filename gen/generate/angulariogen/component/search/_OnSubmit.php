<?php

require_once("generate/GenerateEntity.php");


class ComponentSearchTs_onSubmit extends GenerateEntity {

  public function generate() {
    $this->start();
    $this->pk();
    $this->nf();
    $this->fk();
    $this->end();

    return $this->string;
  }


  protected function start(){
    $this->string .= "  onSubmit(): void {
    this.display.filters = [];
    for(let i = 0; i < this.filters.length; i++){
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

    this.display.search = this.display.search = this.searchForm.get('search').value;
    let sid = encodeURI(JSON.stringify(this.display));
    this.router.navigateByUrl('/{$this->entity->getName('xx-yy')}-show?sid=' + sid);
  }

";
  }


  protected function pk(){
    $field = $this->entity->getPk();
    $this->string .= "      if((this.filters[i].field == '" . $field->getName() . "') && (typeof this.filters[i].value == 'object')){
        var filter = [this.filters[i].field, this.filters[i].option, this.filters[i].value.id];
        this.display.filters.push(filter);
      }
";
  }


  protected function date(Field $field){
    $this->string .= "      if(this.filters[i].field == '" . $field->getName() . "' && this.filters[i].value) {
        var value = this.dd.parser.dateFormat(this.filters[i].value);
        var filter = [this.filters[i].field, this.filters[i].option, value];
        this.display.filters.push(filter);
      }
";
  }

  protected function defecto(Field $field){
    $this->string .= "      if(this.filters[i].field == '" . $field->getName() . "' && this.filters[i].value) {
        var filter = [this.filters[i].field, this.filters[i].option, this.filters[i].value];
        this.display.filters.push(filter);
      }
";
  }

  protected function typeahead(Field $field){
    $this->string .= "      if((this.filters[i].field == '" . $field->getName() . "') && (typeof this.filters[i].value == 'object')){
        var filter = [this.filters[i].field, this.filters[i].option, this.filters[i].value.id];
        this.display.filters.push(filter);
      }
";
  }

}
