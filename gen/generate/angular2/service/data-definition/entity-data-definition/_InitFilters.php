<?php

require_once("generate/GenerateEntity.php");


class EntityDataDefinition_InitFilters extends GenerateEntity {


  public function generate() {
    $this->start();
    $this->pk();
    $this->nf();
    $this->end();

    return $this->string;
  }


  protected function start(){
    $this->string .= "  initFilters(filters: any[]): Observable<any> {
    if(!filters || !filters.length) return of([]);
    let filters_ = [];
    let obs = [];

    for(let i = 0; i < filters.length; i++){
      let filter = {field:filters[i][0], option:filters[i][1], value:filters[i][2]};

      switch(filters[i][0]) {
";
  }


  protected function pk(){
    $field = $this->entity->getPk();
    $this->string .= "        case '" . $field->getName() . "':
          let ob = this.dd.get('" . $this->getEntity()->getName() . "', filters[i][2]).mergeMap(
            row => {
              return this.dd.initLabel('" . $this->getEntity()->getName() . "', row).map(
                row_ => { filter['value'] = row_; }
              )
            }
          );
          obs.push(ob);
        break;
";
  }

  protected function nf(){
    $fields = $this->getEntity()->getFieldsNf();
    foreach($fields as $field){
      switch ( $field->getSubtype() ) {
        case "date": $this->date($field); break;
      }
    }
  }


  protected function end(){
    $this->string .= "      }
      filters_.push(filter)
    }

    if(!obs.length) return of(filters_);

    return Observable.forkJoin(obs).map(
      responses => {
        return filters_;
      }
    );
  }

";
  }









  protected function date(Field $field){
    $this->string .= "      case '" . $field->getName() . "':
        filter['value'] = this.dd.parser.date(filters_[i][2]);
      break;
";
  }

}
