<?php

require_once("class/model/Entity.php");
require_once("GenerateEntity.php");

class Sqlo_update extends GenerateEntity{


  protected function start(){
    $this->string .= "  protected function _update(array \$row){ //@override
    \$sql = \"
UPDATE \" . \$this->entity->sn_() . \" SET
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
