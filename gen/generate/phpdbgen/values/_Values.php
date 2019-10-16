<?php

require_once("class/model/Entity.php");
require_once("generate/GenerateFileEntity.php");

class _ClassValues extends GenerateFileEntity {

  public function __construct(Entity $entity) {
    $directorio = PATH_ROOT."src/class/model/values/" . $entity->getName("xxYy") . "/";
    $nombreArchivo = "_" . $entity->getName("XxYy") . ".php";
    parent::__construct($directorio, $nombreArchivo, $entity);
  }

  protected function generateCode(){
    $this->start();
    $this->body();
    $this->end();
  }

  protected function start(){
    $this->string .= "<?php

require_once(\"class/Format.php\");
require_once(\"class/model/Values.php\");

class _" . $this->getEntity()->getName("XxYy") . " extends EntityValues {
";
  }

  protected function body(){
    $this->properties();
    $this->setDefault();
    $this->fromArray();
    $this->toArray();
    $this->isEmpty();

    $this->getters();
    $this->setters();
    $this->validators();
  }

  protected function properties(){
    foreach($this->entity->getFieldsByType(["pk", "nf", "fk"]) as $field) $this->string .= "  protected \${$field->getName('xxYy')} = UNDEFINED;
";
  }

  protected function setDefault(){
    require_once("generate/phpdbgen/values/setDefault.php");
    $g = new GenValues_setDefault($this->getEntity());
    $this->string .=  $g->generate();
  }

  protected function toArray(){
    require_once("generate/phpdbgen/values/toArray.php");
    $g = new Values_toArray($this->getEntity());
    $this->string .=  $g->generate();
  }

  protected function isEmpty(){
    require_once("generate/phpdbgen/values/isEmpty.php");
    $g = new Values_isEmpty($this->getEntity());
    $this->string .=  $g->generate();
  }

  protected function fromArray(){
    require_once("generate/phpdbgen/values/fromArray.php");
    $g = new ClassValues_fromArray($this->getEntity());
    $this->string .=  $g->generate();
  }

  protected function getters(){
    require_once("generate/phpdbgen/values/getters.php");
    $g = new ClassValues_getters($this->getEntity());
    $this->string .=  $g->generate();
  }

  protected function setters(){
    require_once("generate/phpdbgen/values/setters.php");
    $g = new ClassValues_setters($this->getEntity());
    $this->string .=  $g->generate();
  }

  protected function validators(){
    require_once("generate/phpdbgen/values/validators.php");
    $g = new ClassValues_validators($this->getEntity());
    $this->string .=  $g->generate();
  }

  protected function end(){
    $this->string .= "

}
" ;
  }





}
