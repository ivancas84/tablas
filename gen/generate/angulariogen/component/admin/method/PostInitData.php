<?php

require_once("generate/GenerateEntity.php");


class AdminTs_PostInitData extends GenerateEntity {  

  public function generate() {    
    $this->start();
    $this->body();
    $this->end();

    return $this->string;
  }

  protected function start() {
    $this->string .= "  postInitData(data: { [index: string]: Entity }): void {
    let obs = [];
";
  }

  protected function body() {
    foreach($this->getEntity()->getFieldsFk() as $field){
      if($field->getSubtype() == "typeahead") {
        $this->string .= "    if(data[this.entity]['{$field->getName()}']){
      var ob = this.dd.getOrNull('{$field->getEntityRef()->getName()}', data[this.entity]['{$field->getName()}']).pipe(first());
      obs.push(ob);
    }
";
      }
    }
  }



  protected function end() {
    $this->string .= "    forkJoin(obs).subscribe(
      response => { this.setDataEntity(data); }
    )
  }

";
  }

}
