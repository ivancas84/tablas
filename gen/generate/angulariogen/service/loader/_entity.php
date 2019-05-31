<?php

require_once("generate/GenerateFile.php");

class LoaderService_entity extends Generate {

  protected $structure; //estructura de tablas

  public function __construct(array $structure){
    $this->structure = $structure;
  }



  protected function start(){
    $this->string .= "  entity(name: string, params = null): Entity {
    switch(name) {
";
  }

  protected function body(){
    foreach($this->structure as $entity){
      $this->string .= "        case \"" . $entity->getName() . "\": { return new " . $entity->getName("XxYy") . "(params); }
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
