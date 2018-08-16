<?php


/**
 * Generar clase
 */
class GenerateClassSqlMain extends GenerateFileEntity{

  public function __construct(Entity $entity) {
    $dir = PATH_ROOT."api/class/model/sql/" . $entity->getName("xxYy") . "/";
    parent::__construct($dir, "Main.php", $entity);
  }

  protected function generateCode(){
    $this->start();
    $this->mappingField();
    $this->methodFields();
    $this->methodJoin();
    $this->conditionSearch();
    $this->filters();
    $this->conditionAux();
    //$this->order(); hay un metodo general que resuelve la tarea de ordenamiento para ambos motores
    $this->end();
  }

  protected function start(){
    $this->string .= "<?php
require_once(\"class/model/Sql.php\");

class " .  $this->getEntity()->getName("XxYy") . "SqlMain extends EntitySql{

  public function __construct(){
    \$this->entity = new " . $this->getEntity()->getName("XxYy") . "Entity;
    \$this->db = Dba::dbInstance();
  }
";
  }

  protected function end(){
    $this->string .= "

}
" ;
  }


  protected function mappingField(){
    require_once("generate/phpdbgen/sql/method/_MappingField.php");
    $gen = new ClassSql__mappingField($this->getEntity());
    $this->string .= $gen->generate();

    require_once("generate/phpdbgen/sql/method/MappingField.php");
    $gen = new ClassSql_mappingField($this->getEntity());
    $this->string .= $gen->generate();


  }

  protected function methodFields(){

    require_once("generate/phpdbgen/sql/method/fields/Full.php");
    require_once("generate/phpdbgen/sql/method/fields/Fields.php");
    require_once("generate/phpdbgen/sql/method/fields/label/Label.php");
    require_once("generate/phpdbgen/sql/method/fields/label/_Label.php");
    require_once("generate/phpdbgen/sql/method/fields/label/Full.php");



    $gen = new ClassSql_fields($this->getEntity());
    $this->string .= $gen->generate();

    $gen = new ClassSql_fieldsFull($this->getEntity());
    $this->string .= $gen->generate();

    $gen = new ClassSql__fieldsLabel($this->getEntity());
    $this->string .= $gen->generate();

    $gen = new ClassSql_fieldsLabel($this->getEntity());
    $this->string .= $gen->generate();

    $gen = new ClassSql_fieldsLabelFull($this->getEntity());
    $this->string .= $gen->generate();

    require_once("generate/phpdbgen/sql/method/fields/Aux.php");
    $gen = new ClassSql_fieldsAux($this->getEntity());
    $this->string .= $gen->generate();
  }

  protected function methodJoin(){
    require_once("generate/phpdbgen/sql/method/Join.php");
    $gen = new ClassSql_join($this->getEntity());
    $this->string .= $gen->generate();

    require_once("generate/phpdbgen/sql/method/JoinAux.php");
    $gen = new ClassSql_joinAux($this->getEntity());
    $this->string .= $gen->generate();
  }

  protected function conditionSearch(){
    require_once("generate/phpdbgen/sql/method/ConditionSearch.php");

    $gen = new ClassSql_conditionSearch($this->getEntity());
    $this->string .= $gen->generate();

  }



  protected function filters(){
    require_once("generate/phpdbgen/sql/method/_ConditionAdvanced.php");
    $gen = new ClassSql__conditionAdvanced($this->getEntity());
    $this->string .= $gen->generate();

    require_once("generate/phpdbgen/sql/method/ConditionAdvancedMain.php");
    $gen = new ClassSql_conditionAdvancedMain($this->getEntity());
    $this->string .= $gen->generate();
  }

  protected function conditionAux(){
    require_once("generate/phpdbgen/sql/method/ConditionAux.php");
    $gen = new ClassSql_conditionAux($this->getEntity());
    $this->string .= $gen->generate();

  }

  //Este metodo funciona pero actualmente no se genera, se utiliza un método más sencillo que resuelve el problema del ordemiento
  protected function order(){
    require_once("generate/phpdbgen/sql/method/Order.php");
    $gen = new ClassSql_order($this->getEntity());
    $this->string .= $gen->generate();
  }



}
