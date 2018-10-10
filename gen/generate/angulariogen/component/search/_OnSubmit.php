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
    for(let i = 0; i < this.filters.controls.length; i++){
      switch(this.getField(i)){
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
    $this->string .= "      }
    }

    this.display.search = this.searchForm.get('search').value;
    let sid = encodeURI(JSON.stringify(this.display));
    this.router.navigateByUrl('/{$this->entity->getName('xx-yy')}-show?sid=' + sid);
  }

";
  }


  protected function pk(){
    $field = $this->entity->getPk();
    $this->string .= "        case '" . $field->getName() . "':
          if (typeof this.getValue(i) == 'object') this.assignFilter(this.getField(i), this.getOption(i), this.getValue(i).id);
        break;
";
  }


  protected function date(Field $field){
    $this->string .= "        case '" . $field->getName() . "':
          if(this.getValue(i)) {
            var value = this.dd.parser.dateFormat(this.getValue(i));
            this.assignFilter(this.getField(i), this.getOption(i), value);
          }
        break;
";
  }

  protected function defecto(Field $field){
    $this->string .= "        case '" . $field->getName() . "':
          if(this.getValue(i)) this.assignFilter(this.getField(i), this.getOption(i), this.getValue(i));
        break;
";
  }

  protected function typeahead(Field $field){
    $this->string .= "       case '" . $field->getName() . "':
          if(this.getValue(i) == 'object') if(this.getValue(i)) this.assignFilter(this.getField(i), this.getOption(i), this.getValue(i).id);
        break;
";
  }

}
