<?php

class GeneratePhp {

  protected $structure;

  public function __construct(array $structure) {
    $this->structure = $structure;
  }


  public function dba(){
    require_once("generate/php/dba/Dba.php");
    $gen = new ClassDba();
    $gen->generateIfNotExists();
  }

  protected function sqlo(Entity $entity){
    require_once("generate/php/sqlo/Main.php");
    $gen = new ClassSqloMain($entity);
    $gen->generate();

    require_once("generate/php/sqlo/Sql.php");
    $gen = new ClassSqlo($entity);
    $gen->generate();
  }

  protected function sql(Entity $entity){
    require_once("generate/php/sql/Sql.php");
    $gen = new GenerateClassSql($entity);
    $gen->generate();

    require_once("generate/php/sql/Main.php");
    $gen = new GenerateClassSqlMain($entity);
    $gen->generate();

    //postgres
    //require_once("generate/php/sql/pg/Main.php");
    //$gen = new GenerateClassSqlPgMain($this->entity);
    //$gen->generate();

  }

  protected function IncludeModelClasses(){
    require_once("generate/php/includeModelClasses/IncludeModelClasses.php");
    $gen = new IncludeModelClasses($this->structure);
    $gen->generate();
  }

  public function generate(){
    $this->dba();
    $this->includeModelClasses();

    foreach($this->structure as $entity) {
      $this->sqlo($entity);
      $this->sql($entity);
    }
  }





}
