<?php


/**
 * Generar clase
 */
class GenerateClassSqlMain extends GenerateFileEntity{

  public function __construct(Entity $entity) {
    $dir = PATH_ROOT."api/class/model/sql/" . $entity->getName("xxYy") . "/";
    parent::__construct($dir, "Main.php", $entity);
  }



  protected function start(){
    $this->string .= "<?php
require_once(\"class/model/Sql.php\");

class " .  $this->getEntity()->getName("XxYy") . "SqlMain extends EntitySql{

  public function __construct(){
    \$this->entity = new " . $this->getEntity()->getName("XxYy") . "Entity;
  }
";
  }

  protected function end(){
    $this->string .= "

}
" ;
  }


  protected function mappingField(){
    require_once("generate/php/sql/method/_MappingField.php");
    $gen = new ClassSql__mappingField($this->getEntity());
    $this->string .= $gen->generate();

    require_once("generate/php/sql/method/MappingField.php");
    $gen = new ClassSql_mappingField($this->getEntity());
    $this->string .= $gen->generate();


  }

  protected function methodFields(){

    require_once("generate/php/sql/method/fields/Full.php");
    require_once("generate/php/sql/method/fields/Fields.php");
    require_once("generate/php/sql/method/fields/label/Label.php");
    require_once("generate/php/sql/method/fields/label/Full.php");


    $gen = new ClassSql_fields($this->getEntity());
    $this->string .= $gen->generate();

    $gen = new ClassSql_fieldsFull($this->getEntity());
    $this->string .= $gen->generate();

    $gen = new ClassSql_fieldsLabel($this->getEntity());
    $this->string .= $gen->generate();

    $gen = new ClassSql_fieldsLabelFull($this->getEntity());
    $this->string .= $gen->generate();
  }

  protected function methodJoin(){
    require_once("generate/php/sql/method/Join.php");

    $gen = new ClassSql_join($this->getEntity());
    $this->string .= $gen->generate();
  }

  protected function conditionSearch(){
    require_once("generate/php/sql/method/ConditionSearch.php");

    $gen = new ClassSql_conditionSearch($this->getEntity());
    $this->string .= $gen->generate();

  }



  protected function conditionAdvancedSearch(){
    require_once("generate/php/sql/method/ConditionAdvancedSearch.php");

    $gen = new ClassSql_conditionAdvancedSearch($this->getEntity());
    $this->string .= $gen->generate();
  }




  protected function generateCode(){
    $this->start();
    $this->mappingField();
    $this->methodFields();
    $this->methodJoin();
    $this->conditionSearch();
    $this->conditionAdvancedSearch();
    $this->end();
  }

}
