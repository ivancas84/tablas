<?php

require_once("class/model/Entity.php");
require_once("generate/GenerateEntity.php");

class Sqlo_deleteAll extends GenerateEntity {

  protected $history = null;

  public function generate(){
    /**
     * Se sobrescribe solo si existen campos historicos para implementar eliminacion logica
     */
    $history = false;
    foreach($this->entity->getFields() as $field) {
      if($field->isHistory()) $this->history = $field; 
    }

    if(!$history) return;
    
    $this->body();
    return $this->string;
  }

  protected function body(){
    $this->string .= "  \$row = ['{$this->history->getName()}' => date('Y-m-d H:i:s')];
    return \$this->updateAll(\$row, \$ids);
";
  }

}
