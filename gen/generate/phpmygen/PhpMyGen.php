<?php

class PhpMyGen {

  protected $structure;

  public function __construct(array $structure) {
    $this->structure = $structure;
  }


  public function dba(){
    require_once("generate/phpmygen/dba/Dba.php");
    $gen = new ClassDba();
    $gen->generateIfNotExists();
  }

  protected function sqlo(Entity $entity){
    require_once("generate/phpmygen/sqlo/Main.php");
    $gen = new ClassSqloMain($entity);
    $gen->generate();

    require_once("generate/phpmygen/sqlo/Sql.php");
    $gen = new ClassSqlo($entity);
    $gen->generateIfNotExists();
  }

  protected function sql(Entity $entity){
    require_once("generate/phpmygen/sql/Sql.php");
    $gen = new GenerateClassSql($entity);
    $gen->generateIfNotExists();

    require_once("generate/phpmygen/sql/Main.php");
    $gen = new GenerateClassSqlMain($entity);
    $gen->generate();

    //postgres
    //require_once("generate/phpmygen/sql/pg/Main.php");
    //$gen = new GenerateClassSqlPgMain($this->entity);
    //$gen->generate();

  }

  protected function IncludeModelClasses(){
    require_once("generate/phpmygen/includeModelClasses/IncludeModelClasses.php");
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
