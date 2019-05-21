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
    require_once("generate/phpdbgen/values/Main.php");
    $gen = new ClassValuesMain($entity);
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

  public function generate(){
    $this->includes();

    foreach($this->structure as $entity) {
      $this->doc($entity);
      $this->sqlo($entity);
      $this->sql($entity);
      $this->values($entity);
    }
  }





}
