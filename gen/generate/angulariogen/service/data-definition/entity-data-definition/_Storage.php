<?php

require_once("generate/GenerateEntity.php");


class EntityDataDefinition_Storage extends GenerateEntity {

  public function generate() {
    $this->start();
    $this->recursive($this->getEntity());
    $this->end();

    return $this->string;
  }

  protected function start(){
    $this->string .= "  storage(row: { [index: string]: any }){
    if(!row) return;
";
  }

  protected function end(){
    $this->string .= "    this.dd.storage.setItem(\"" . $this->getEntity()->getName() . "\" + row.id, row);
  }

";
  }


  protected function recursive(Entity $entity, array $tablesVisited = NULL, array $names = []) {

    if(is_null($tablesVisited)) $tablesVisited = array();

    $this->fk($entity, $tablesVisited, $names);
    $this->u_($entity, $tablesVisited, $names);


     if (!empty($names)) $this->fields($entity->getName(), $names);
  }

  protected function fields($tableName, array $names){
    $row = "row";
    $key = $names[count($names)-1];
    for($i =0; $i < count($names) -1 ; $i++) $row .= "['{$names[$i]}']";
    $row_ = $row . "['{$key}']";


    $this->string .= "    if(('{$key}' in {$row}) && ({$row_}.id !=  'undefined')){
      this.dd.storage.setItem('{$tableName}' + {$row_}.id, {$row_});
      delete {$row_};
    }
";
  }

  protected function fk(Entity $entity, array $tablesVisited, array $names){
    $fk = $entity->getFieldsFkNotReferenced($tablesVisited);
    array_push($tablesVisited, $entity->getName());

    foreach ($fk as $field ) {
      $namesAux = $names;
      array_push($namesAux, $field->getName() . "_");

      $this->recursive($field->getEntityRef(), $tablesVisited, $namesAux) ;
    }
  }

  protected function u_(Entity $entity, array $tablesVisited, array $names){
    $u_ = $entity->getFieldsU_NotReferenced($tablesVisited);
    array_push($tablesVisited, $entity->getName());

    foreach ($u_ as $field ) {
      $namesAux = $names;
      array_push($namesAux, $field->getAlias("_") . "_");
      $this->fields($field->getEntity()->getName(), $namesAux);
    }
  }

}
