<?php

require_once("generate/GenerateEntityRecursive.php");

class ClassSql_conditionAux extends GenerateEntityRecursive{

  protected $conditions = [];
  public function generate(){
    $this->start();
    $this->recursive($this->getEntity());
    $this->end();

    return $this->string;
  }

  protected function start(){
    $this->string .= "  //@override
  public function conditionAux() {
    return \"(\" . self::_conditionAux() .
";
  }

  protected function body(Entity $entity, $prefix){
    array_push($this->conditions, "{$entity->getName("XxYy")}Sql::_conditionAux('{$prefix}')");
  }

  protected function end(){
    if(!empty( $this->conditions)) {
      $conditions = implode(" .
    \" AND \" . ", $this->conditions);

    $this->string .= "    \" AND \" . {$conditions} . \")\";
  }

";
    } else {
        $this->string .= " \")\";
      }

      ";
    }
  }







}
