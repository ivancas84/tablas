<?php

require_once("generate/Generate.php");

class LoaderService_dataDefinition extends Generate {

  protected $structure; //estructura de tablas

  public function __construct(array $structure){
    $this->structure = $structure;
  }


  protected function start(){
    $this->string .= "  dataDefinition(name: string, dd: DataDefinitionService): DataDefinition {
    switch(name) {
";
  }

  protected function body(){
    foreach($this->structure as $entity){
      $this->string .= "        case \"" . $entity->getName() . "\": { return new " . $entity->getName("XxYy") . "DataDefinition(dd); }
";
      }
  }

  protected function end(){
    $this->string .= "     }
  }
";
  }

  public function generate(){
    $this->start();
    $this->body();
    $this->end();
    return $this->string;
  }


}
