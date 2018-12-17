<?php

require_once("class/model/Entity.php");
require_once("generate/GenerateFileEntity.php");

//Generar codigo de clase
class ClassSqloMain extends GenerateFileEntity {

  public function __construct(Entity $entity) {
    $directorio = PATH_ROOT."api/class/model/sqlo/" . $entity->getName("xxYy") . "/";
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

require_once(\"class/model/Sqlo.php\");

//Implementacion principal de Sqlo para una entidad especifica
class " . $this->getEntity()->getName("XxYy") . "SqloMain extends EntitySqlo {
";
  }

  protected function end(){
    $this->string .= "

}
" ;
  }

  protected function construct(){
    $this->string .= "
  //Constructor
  //Se definen todos los recursos de forma independiente, sin parametros en el constructor, para facilitar el polimorfismo de las subclases
  public function __construct(){
    \$this->db = Dba::dbInstance();
    \$this->entity = new " . $this->getEntity()->getName("XxYy") . "Entity;
    \$this->sql = new " . $this->getEntity()->getName("XxYy") . "Sql;
  }
";
  }

  protected function insert(){
    require_once("generate/phpdbgen/sqlo/method/Insert.php");
    $g = new Sqlo_insert($this->getEntity());
    $this->string .=  $g->generate();
  }



  protected function initializeInsert(){
    require_once("generate/phpdbgen/sqlo/method/InitializeInsert.php");
    $gen = new initializeInsert($this->getEntity());
    $this->string .= $gen->generate();
  }

  protected function initializeUpdate(){
    require_once("generate/phpdbgen/sqlo/method/InitializeUpdate.php");
    $gen = new initializeUpdate($this->getEntity());
    $this->string .= $gen->generate();
  }

  protected function format(){
    require_once("generate/phpdbgen/sqlo/method/Format.php");
    $gen = new Sqlo_FormatSql($this->getEntity());
    $this->string .= $gen->generate();
  }


  protected function update(){
    require_once("generate/phpdbgen/sqlo/method/Update.php");
    $gen = new Sqlo_update($this->getEntity());
    $this->string .= $gen->generate();
  }

  protected function uploadSql(){
    require_once("generate/phpdbgen/sqlo/method/UploadSql.php");
    $gen = new GenerateClassDataSqlMethodUploadSql($this->getEntity());
    $this->string .= $gen->generate();
  }

  protected function uploadSqlIndex(){
    require_once("generate/phpdbgen/sqlo/method/UploadSqlIndex.php");
    $this->string .= GenerateClassDataSqlMethodUploadSqlIndex::createAndGetString($this->getEntity());
  }






  protected function values(){
    require_once("generate/phpdbgen/sqlo/method/_Values.php");
    $gen = new ClassSqlo__values($this->getEntity());
    $this->string .= $gen->generate();

  }


  protected function body(){
    $this->construct();
    $this->insert();
    $this->initializeInsert();
    $this->update();
    $this->initializeUpdate();
    $this->format();
    $this->uploadSql();
    $this->uploadSqlIndex();
    //$this->values(); deprecated
   }


}
