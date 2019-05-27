<?php

require_once("generate/GenerateEntity.php");


class ComponentSearchTs_onSubmit extends GenerateEntity {

  public function generate() {
    $this->start();
    $this->pk();
    $this->nf();
    //$this->fk();
    $this->end();

    return $this->string;
  }


  protected function start(){
    $this->string .= "  onSubmit(): void {
    let d = {search:null, filters:[]}
    d.filters = [];
    for(let i = 0; i < this.filters.controls.length; i++){
      switch(this.f(i)){
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

    d.search = this.searchForm.get('search').value;
    this.changeSearch.emit(d);
  }

";
  }


  protected function pk(){
    $field = $this->entity->getPk();
    $this->string .= "        case '" . $field->getName() . "':
          if (typeof this.v(i) == 'object') d.filters.push([this.f(i), this.o(i), this.v(i).id]);
        break;
";
  }


  protected function date(Field $field){
    $this->string .= "        case '" . $field->getName() . "':
          //TODO: Verificar el formulario de administracion y copiar la implementacion de date
        break;
";
  }

  protected function defecto(Field $field){
    $this->string .= "        case '" . $field->getName() . "':
          if(this.v(i)) d.filters.push([this.f(i), this.o(i), this.v(i).id]);
        break;
";
  }

  protected function typeahead(Field $field){
    $this->string .= "       case '" . $field->getName() . "':
          if(this.v(i) == 'object') if(this.v(i)) this.assignFilter(this.f(i), this.o(i), this.v(i).id);
        break;
";
  }

}
