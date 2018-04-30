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
require_once(\"class/db/SqlFormat.php\");

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
    \$this->entity = new " . $this->getEntity()->getName("XxYy") . "Entity;
    \$this->sql = new " . $this->getEntity()->getName("XxYy") . "Sql;
  }
";
  }

  protected function insertSql(){
    require_once("generate/php/sqlo/method/InsertSql.php");
    $g = new GenerateClassDataSqlMethodInsertSql($this->getEntity());
    $this->string .=  $g->generate();
  }



  protected function initializeInsertSql(){
    require_once("generate/php/sqlo/method/InitializeInsertSql.php");
    $gen = new InitializeInsertSql($this->getEntity());
    $this->string .= $gen->generate();
  }

  protected function initializeUpdateSql(){
    require_once("generate/php/sqlo/method/InitializeUpdateSql.php");
    $gen = new InitializeUpdateSql($this->getEntity());
    $this->string .= $gen->generate();
  }

  protected function formatSql(){
    require_once("generate/php/sqlo/method/FormatSql.php");
    $gen = new Sqlo_FormatSql($this->getEntity());
    $this->string .= $gen->generate();
  }


  protected function updateSql(){
    require_once("generate/php/sqlo/method/UpdateSql.php");
    $gen = new Sqlo_updateSql($this->getEntity());
    $this->string .= $gen->generate();
  }

  protected function uploadSql(){
    require_once("generate/php/sqlo/method/UploadSql.php");
    $gen = new GenerateClassDataSqlMethodUploadSql($this->getEntity());
    $this->string .= $gen->generate();
  }

  protected function uploadSqlIndex(){
    require_once("generate/php/sqlo/method/UploadSqlIndex.php");
    $this->string .= GenerateClassDataSqlMethodUploadSqlIndex::createAndGetString($this->getEntity());
  }

  protected function _build(){
    require_once("generate/php/sqlo/method/_Build.php");
    $gen = new ClassSqlo__build($this->getEntity());
    $this->string .= $gen->generate();
  }

  protected function build(){
    require_once("generate/php/sqlo/method/Build.php");
    $gen = new ClassSqlo_build($this->getEntity());
    $this->string .= $gen->generate();
  }



  protected function body(){
    $this->construct();
    $this->insertSql();
    $this->initializeInsertSql();
    $this->updateSql();
    $this->initializeUpdateSql();
    $this->formatSql();
    $this->uploadSql();
    $this->uploadSqlIndex();
    $this->_build();
    $this->build();

   }


}
