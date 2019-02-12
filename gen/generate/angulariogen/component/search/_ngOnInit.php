<?php

require_once("generate/GenerateEntity.php");

class ComponentSearchTs_ngOnInit extends GenerateEntity {

  protected $entities = [];

  public function generate() {
    $this->defineEntityRecursive($this->entity);
  
    if(!count($this->entities)) return;

    $this->start();
    $this->body();
    $this->end();
    return $this->string;
  }




  protected function start(){
    $this->string .= "  ngOnInit() { //definir opciones del formulario
";
}

  protected function body(){
    $entities = implode("'", array_unique($this->entities));
    $this->string .= "    this.dd.entitiesAll(['{$entities}']).subscribe(
      options => { this.options = options; }
    );
";

}

  protected function end(){
    $this->string .= "  }
";

  }





  protected function defineEntityRecursive(Entity $entity, array $tablesVisited = NULL, $prefix = ""){
   if(is_null($tablesVisited)) $tablesVisited = array($entity->getName());
   $this->string .= $this->defineEntity($entity, $prefix);
   $this->defineEntityFk($entity, $tablesVisited, $prefix);
  }

  protected function defineEntity(Entity $entity, $prefix){
    foreach($entity->getFieldsFk() as $field) {
      switch($field->getSubtype()){
        case "select": array_push($this->entities, $field->getEntityRef()->getName()); break;
      }
    }
  }

  protected function defineEntityFk(Entity $entity, array $tablesVisited, $prefix) {
   $fk = $entity->getFieldsFkNotReferenced($tablesVisited);
   foreach ($fk as $field) {
     array_push($tablesVisited, $entity->getName());
     $this->string .= $this->defineEntityRecursive($field->getEntityRef(), $tablesVisited, $prefix . $field->getAlias() . "_") ;
   }
  }



}
