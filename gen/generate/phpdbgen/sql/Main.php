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
    $this->conditionHistory();
    $this->initializeInsert();
    $this->initializeUpdate();
    $this->format();
    $this->json(); //este metodo no coincide con la responsabilidad de la clase SQL pero por el momento se deja aqui hasta encontrar un lugar mas apropiado
    //$this->values(); este metodo transforma el resultado json en values, pero por el momento se descarta
    //$this->order(); hay un metodo general que resuelve la tarea de ordenamiento para ambos motores
    $this->end();
  }

  protected function start(){
    $this->string .= "<?php
require_once(\"class/model/Sql.php\");

class " .  $this->getEntity()->getName("XxYy") . "SqlMain extends EntitySql{

  public function __construct(){
    parent::__construct();
    \$this->entity = Entity::getInstanceFromString('{$this->getEntity()->getName()}');
  }


";
  }

  protected function end(){
    $this->string .= "

}
" ;
  }

  protected function values(){
    require_once("generate/phpdbgen/sql/method/Values.php");
    $gen = new ClassSql_values($this->getEntity());
    $this->string .= $gen->generate();
  }

    protected function json(){
      require_once("generate/phpdbgen/sql/method/_Json.php");
      $gen = new ClassSql__json($this->getEntity());
      $this->string .= $gen->generate();
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
    require_once("generate/phpdbgen/sql/method/fields/Fields.php");
    $gen = new ClassSql_fields($this->getEntity());
    $this->string .= $gen->generate();

    require_once("generate/phpdbgen/sql/method/fields/Full.php");
    $gen = new ClassSql_fieldsFull($this->getEntity());
    $this->string .= $gen->generate();

    require_once("generate/phpdbgen/sql/method/fields/Aux2.php");
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

    require_once("generate/phpdbgen/sql/method/_ConditionSearch.php");
    $gen = new ClassSql__conditionSearch($this->getEntity());
    $this->string .= $gen->generate();

  }



  protected function filters(){
    require_once("generate/phpdbgen/sql/method/_ConditionAdvanced.php");
    $gen = new ClassSql__conditionAdvanced($this->getEntity());
    $this->string .= $gen->generate();

    require_once("generate/phpdbgen/sql/method/ConditionAdvancedMain.php");
    $gen = new ClassSql_conditionAdvancedMain($this->getEntity());
    $this->string .= $gen->generate();

    require_once("generate/phpdbgen/sql/method/ConditionAdvancedAux.php");
    $gen = new ClassSql_conditionAdvancedAux($this->getEntity());
    $this->string .= $gen->generate();
  }

  protected function conditionAux(){
    require_once("generate/phpdbgen/sql/method/ConditionAux.php");
    $gen = new ClassSql_conditionAux($this->getEntity());
    $this->string .= $gen->generate();
  }

  protected function conditionHistory(){
    require_once("generate/phpdbgen/sql/method/ConditionHistory.php");
    $gen = new ClassSql_conditionHistory($this->getEntity());
    $this->string .= $gen->generate();

    require_once("generate/phpdbgen/sql/method/_ConditionHistory.php");
    $gen = new ClassSql__conditionHistory($this->getEntity());
    $this->string .= $gen->generate();
  }

  //Este metodo funciona pero actualmente no se genera, se utiliza un método más sencillo que resuelve el problema del ordemiento
  protected function order(){
    require_once("generate/phpdbgen/sql/method/Order.php");
    $gen = new ClassSql_order($this->getEntity());
    $this->string .= $gen->generate();
  }

  protected function initializeInsert(){
    require_once("generate/phpdbgen/sql/method/InitializeInsert.php");
    $gen = new Sql_initializeInsert($this->getEntity());
    $this->string .= $gen->generate();
  }

  protected function initializeUpdate(){
    require_once("generate/phpdbgen/sql/method/InitializeUpdate.php");
    $gen = new Sql_initializeUpdate($this->getEntity());
    $this->string .= $gen->generate();
  }

  protected function format(){
    require_once("generate/phpdbgen/sql/method/Format.php");
    $gen = new Sql_FormatSql($this->getEntity());
    $this->string .= $gen->generate();
  }

}
