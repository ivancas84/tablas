<?php

require_once("class/model/Entity.php");
require_once("GenerateEntity.php");

class Sqlo_insert extends GenerateEntity {

  public function generate(){
    $this->start();
    $this->fieldNames();
    $this->deleteLastComma();
    $this->sqlValues();
    $this->fieldValues();
    $this->deleteLastComma();
    $this->end();

    return $this->string;
  }


  protected function start(){
      $this->string .= "  protected function _insert(array \$row){ //@override
      \$sql = \"
  INSERT INTO \" . \$this->entity->sn_() . \" (\";
  ";
    }

  protected function fieldNames(){ //generar nombres de fields
    foreach ( $this->getEntity()->getFields() as $field) {
      if(!$field->isAdmin()) continue;
      $this->string .= "    \$sql .= \"" . $field->getName() . ", \" ;
" ;
    }
  }

  protected function fieldValues(){   //generar valores de fields
    foreach($this->getEntity()->getFields() as $field){
      if(!$field->isAdmin()) continue;
      $this->string .= "    \$sql .= \$row['" . $field->getName() . "'] . \", \" ;
" ;
    }
  }

  protected function deleteLastComma(){
    $this->string .= "    \$sql = substr(\$sql, 0, -2); //eliminar ultima coma
";
  }

  protected function sqlValues(){
    $this->string .= "
    \$sql .= \")
VALUES ( \";
" ;
  }

  protected function end(){
    $this->string .=  "
    \$sql .= \");
\";

    return \$sql;
  }

";
  }

}
