<?php

class PhpDbGen {

  protected $structure;

  public function __construct(array $structure) {
    $this->structure = $structure;
  }

  protected function doc(Entity $entity){
    require_once("generate/phpdbgen/doc/Main.php");
    $gen = new Doc($entity);
    $gen->generate();
  }

  protected function sqlo(Entity $entity){
    require_once("generate/phpdbgen/sqlo/Main.php");
    $gen = new ClassSqloMain($entity);
    $gen->generate();

    require_once("generate/phpdbgen/sqlo/Sqlo.php");
    $gen = new ClassSqlo($entity);
    $gen->generateIfNotExists();
  }

  protected function sql(Entity $entity){
    require_once("generate/phpdbgen/sql/Sql.php");
    $gen = new GenerateClassSql($entity);
    $gen->generateIfNotExists();

    require_once("generate/phpdbgen/sql/Main.php");
    $gen = new GenerateClassSqlMain($entity);
    $gen->generate();

    //postgres
    //require_once("generate/phpdbgen/sql/pg/Main.php");
    //$gen = new GenerateClassSqlPgMain($this->entity);
    //$gen->generate();

  }

  protected function values(Entity $entity){
    require_once("generate/phpdbgen/values/_Values.php");
    $gen = new _ClassValues($entity);
    $gen->generate();

    require_once("generate/phpdbgen/values/Values.php");
    $gen = new ClassValues($entity);
    $gen->generateIfNotExists();
  }


  protected function Includes(){
    require_once("generate/phpdbgen/include/IncludeModelClasses.php");
    $gen = new IncludeModelClasses($this->structure);
    $gen->generate();

    require_once("generate/phpdbgen/include/IncludeValuesClasses.php");
    $gen = new IncludeValuesClasses($this->structure);
    $gen->generate();
  }

  protected function api(Entity $entity){
    require_once("generate/phpdbgen/api/All.php");
    $gen = new Gen_AllApi($entity);
    $gen->generateIfNotExists();

    require_once("generate/phpdbgen/api/Count.php");
    $gen = new Gen_CountApi($entity);
    $gen->generateIfNotExists();

    require_once("generate/phpdbgen/api/GetAll.php");
    $gen = new Gen_GetAllApi($entity);
    $gen->generateIfNotExists();

    require_once("generate/phpdbgen/api/Ids.php");
    $gen = new Gen_IdsApi($entity);
    $gen->generateIfNotExists();


    require_once("generate/phpdbgen/api/Unique.php");
    $gen = new Gen_UniqueApi($entity);
    $gen->generateIfNotExists();
    
  }

  public function generate(){
    //$this->includes();

    foreach($this->structure as $entity) {
      $this->api($entity);
      $this->doc($entity);
      $this->sqlo($entity);
      $this->sql($entity);
      $this->values($entity);
    }
  }





}
