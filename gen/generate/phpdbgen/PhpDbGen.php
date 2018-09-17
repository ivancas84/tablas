<?php

class PhpDbGen {

  protected $structure;

  public function __construct(array $structure) {
    $this->structure = $structure;
  }

  protected function sqlo(Entity $entity){
    require_once("generate/phpdbgen/sqlo/Main.php");
    $gen = new ClassSqloMain($entity);
    $gen->generate();

    require_once("generate/phpdbgen/sqlo/Sql.php");
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

  protected function IncludeModelClasses(){
    require_once("generate/phpdbgen/includeModelClasses/IncludeModelClasses.php");
    $gen = new IncludeModelClasses($this->structure);
    $gen->generate();
  }

  public function generate(){
    $this->includeModelClasses();

    foreach($this->structure as $entity) {
      $this->sqlo($entity);
      $this->sql($entity);
    }
  }





}
