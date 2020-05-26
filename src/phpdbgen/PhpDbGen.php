<?php

class PhpDbGen {

  protected $structure;

  public function __construct(array $structure) {
    $this->structure = $structure;
  }

  protected function doc(Entity $entity){
    require_once("phpdbgen/doc/Main.php");
    $gen = new Doc($entity);
    $gen->generate();
  }

  protected function sqlo(Entity $entity){
    require_once("phpdbgen/sqlo/Main.php");
    $gen = new ClassSqloMain($entity);
    $gen->generate();

    require_once("phpdbgen/sqlo/Sqlo.php");
    $gen = new ClassSqlo($entity);
    $gen->generateIfNotExists();
  }

  protected function sql(Entity $entity){
    require_once("phpdbgen/sql/Sql.php");
    $gen = new GenerateClassSql($entity);
    $gen->generateIfNotExists();

    require_once("phpdbgen/sql/Main.php");
    $gen = new GenerateClassSqlMain($entity);
    $gen->generate();

    //postgres
    //require_once("phpdbgen/sql/pg/Main.php");
    //$gen = new GenerateClassSqlPgMain($this->entity);
    //$gen->generate();

  }

  protected function values(Entity $entity){
    require_once("phpdbgen/values/_Values.php");
    $gen = new _ClassValues($entity);
    $gen->generate();

    require_once("phpdbgen/values/Values.php");
    $gen = new ClassValues($entity);
    $gen->generateIfNotExists();
  }


  protected function Includes(){
    require_once("phpdbgen/include/IncludeModelClasses.php");
    $gen = new IncludeModelClasses($this->structure);
    $gen->generate();

    require_once("phpdbgen/include/IncludeValuesClasses.php");
    $gen = new IncludeValuesClasses($this->structure);
    $gen->generate();
  }

  protected function controller(Entity $entity){
    require_once("phpdbgen/controller/All.php");
    $gen = new Gen_All($entity);
    $gen->generateIfNotExists();

    require_once("phpdbgen/controller/Count.php");
    $gen = new Gen_Count($entity);
    $gen->generateIfNotExists();

    require_once("phpdbgen/controller/GetAll.php");
    $gen = new Gen_GetAll($entity);
    $gen->generateIfNotExists();

    require_once("phpdbgen/controller/Ids.php");
    $gen = new Gen_Ids($entity);
    $gen->generateIfNotExists();


    require_once("phpdbgen/controller/Unique.php");
    $gen = new Gen_Unique($entity);
    $gen->generateIfNotExists();

    require_once("phpdbgen/controller/DisplayRender.php");
    $gen = new Gen_DisplayRender($entity);
    $gen->generateIfNotExists();
    
  }

  public function generate(){
    //$this->includes();

    foreach($this->structure as $entity) {
      $this->controller($entity);
      $this->doc($entity);
      $this->sqlo($entity);
      $this->sql($entity);
      $this->values($entity);
    }
  }





}
