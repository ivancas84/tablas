<?php

require_once("class/model/Entity.php");
require_once("generate/GenerateEntity.php");

class Sqlo_updateSql extends GenerateEntity{


  protected function start(){
    $this->string .= "
  //@override
  protected function _update(array \$row){
    \$sql = \"
UPDATE \" . \$this->entity->getSn_() . \" SET
\";
";
  }



  public function generate(){
    $this->start();
    $this->body();
    $this->end();

    return $this->string;
  }




  protected function body(){
    foreach ( $this->getEntity()->getFieldsByType(["nf","fk"]) as $field ) {
      if(!$field->isAdmin()) continue;
      $this->string .= "    if (isset(\$row['" . $field->getName() . "'] )) \$sql .= \"" . $field->getName() . " = \" . \$row['" . $field->getName() . "'] . \" ,\" ;
";

    }
  }

  protected function end(){
    $this->string .= "    //eliminar ultima coma
    \$sql = substr(\$sql, 0, -2);

    return \$sql;
  }
" ;
  }

}
