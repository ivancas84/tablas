<?php

require_once("generate/GenerateEntity.php");


class ComponentSearchTs_initFilters extends GenerateEntity {


  public function generate() {
    $this->start();
    $this->pk();
    $this->nf();
    $this->end();

    return $this->string;
  }


  protected function start(){
    $this->string .= "  initFilters(): any[] {
    let filtersFGs: FormGroup[] = [];

    for(let i = 0; i < this.display.filters.length; i++){
      let filter = {field:this.display.filters[i][0], option:this.display.filters[i][1], value:this.display.filters[i][2]};
      switch(this.display.filters[i][0]) {
";
  }


  protected function pk(){
    $field = $this->entity->getPk();
    $this->string .= "        case '" . $field->getName() . "':
          filter['value'] = this.dd.storage.getItem('" . $this->getEntity()->getName() . "'+this.display.filters[i][2]);
        break;
";
  }

  protected function nf(){
    $fields = $this->getEntity()->getFieldsNf();
    foreach($fields as $field){
      if($field->isAggregate()) continue;

      switch ( $field->getSubtype() ) {
        case "date": $this->date($field); break;
      }
    }
  }


  protected function end(){
    $this->string .= "      }
      filtersFGs.push(this.fb.group(filter));
    }

    return filtersFGs;
  }

";
  }









  protected function date(Field $field){
    $this->string .= "        case '" . $field->getName() . "':
          filter['value'] = this.dd.parser.date(filtersFGs[i][2]);
        break;
";
  }

}
