<?php

require_once("class/model/Entity.php");
require_once("generate/GenerateFileEntity.php");

//Generar codigo de clase
class ClassValuesMain extends GenerateFileEntity {

  public function __construct(Entity $entity) {
    $directorio = PATH_ROOT."src/class/model/values/" . $entity->getName("xxYy") . "/";
    $nombreArchivo = "Main.php";
    parent::__construct($directorio, $nombreArchivo, $entity);
  }

  protected function generateCode(){
    $this->start();
    $this->body();
    $this->end();
  }

  protected function start(){
    $this->string .= "<?php

require_once(\"class/model/Values.php\");

//Implementacion principal de Values para una entidad especifica
class " . $this->getEntity()->getName("XxYy") . "ValuesMain extends EntityValues {
";
  }

  protected function body(){
    $this->properties();
    $this->fromArray();
    $this->toArray();
    $this->getters();
    $this->setters();

   }


  protected function properties(){
    foreach($this->entity->getFieldsByType(["pk", "nf", "fk"]) as $field) $this->string .= "  public \${$field->getName('xxYy')} = UNDEFINED;
";
  }

  protected function toArray(){
    require_once("generate/phpdbgen/values/toArray.php");
    $g = new Values_toArray($this->getEntity());
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



  protected function end(){
    $this->string .= "

}
" ;
  }





}
