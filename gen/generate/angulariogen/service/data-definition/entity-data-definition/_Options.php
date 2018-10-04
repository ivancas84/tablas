<?php

require_once("generate/GenerateEntity.php");


class EntityDataDefinition_Options extends GenerateEntity {

  protected $options = [];


  public function generate() {
    $this->defineOptions($this->getEntity());
    if(!count($this->options)) return;
    $this->start();
    $this->body();
    $this->end();

    return $this->string;
  }

  protected function defineOptions(Entity $entity, array $tablesVisited = null){
    if(is_null($tablesVisited)) $tablesVisited = array($entity->getName());

    $fk = $entity->getFieldsFkNotReferenced($tablesVisited);
    if(!count($fk)) return;
    foreach($fk as $field){
      if($field->getSubtype() == "select") array_push($this->options, $field);
      array_push($tablesVisited, $entity->getName());
      $this->defineOptions($field->getEntityRef(), $tablesVisited);
    }
  }

  protected function start(){
    $this->string .= "  options(sync: any): Observable<any> {
    let obs = [];
";
  }


  protected function body(){
    foreach($this->options as $field){
      $this->string .= "    if(this.dd.isSync('" . $field->getName() . "', sync)){
      var ob = this.dd.all('" . $field->getEntityRef()->getName() . "').mergeMap(
        rows => { return this.dd.initLabelAll('" . $field->getEntityRef()->getName() . "', rows); }
      );
      obs.push(ob);
    }

";
    }
  }



  protected function end(){
    $this->string .= "    return forkJoin(obs).map(
      options => {
        return {
";
    for($i = 0; $i < count($this->options); $i++){
      $this->string .= "          '" . $this->options[$i]->getName() . "': options[" . $i . "],
";
    }


    $this->string .= "        }
      }
    )
  }

";
  }









  protected function date(Field $field){
    $this->string .= "      if(filters_[i].field == '" . $field->getName() . "') filters_[i].value = this.dd.parser.date(filters_[i].value);
";
  }

}
