<?php

require_once("generate/GenerateFileEntity.php");


class ComponentShowHtml extends GenerateFileEntity {

  public function __construct(Entity $entity, $directorio = null){
    $file = $entity->getName("xx-yy") . "-show.component.html";
    if(!$directorio) $directorio = PATH_GEN . "tmp/component/show/" . $entity->getName("xx-yy") . "-show/";
    parent::__construct($directorio, $file, $entity);
  }


  public function generateCode() {
    $this->string .= "<app-" . $this->getEntity()->getName("xx-yy") . "-search [display]=\"display\" (changeSearch)=\"changeSearch(\$event)\"></app-" . $this->getEntity()->getName("xx-yy") . "-search>
<app-" . $this->getEntity()->getName("xx-yy") . "-table [display]=\"display\" [rows]=\"rows\" [sync]=\"sync\"></app-" . $this->getEntity()->getName("xx-yy") . "-table>
<app-pagination [display]=\"display\" [collectionSize]=\"collectionSize\" (changePage)=\"changePage(\$event)\"></app-pagination>
";

  }
}
