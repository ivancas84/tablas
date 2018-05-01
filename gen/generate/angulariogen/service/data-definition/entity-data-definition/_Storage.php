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


  protected function recursive(Entity $entity, array $tablesVisited = NULL, $arrayName = "") {

    if(is_null($tablesVisited)) $tablesVisited = array();
    if (empty($arrayName)) $arrayName = "row";

    $this->fk($entity, $tablesVisited, $arrayName);
    $this->u_($entity, $tablesVisited, $arrayName);


     if ($arrayName != "row") $this->string .= $this->fields($entity->getName(), $arrayName);
  }


  protected function fk(Entity $entity, array $tablesVisited, $arrayName){
    $fk = $entity->getFieldsFkNotReferenced($tablesVisited);
    foreach ($fk as $field ) {
      array_push($tablesVisited, $entity->getName());
      $this->string .= $this->recursive($field->getEntityRef(), $tablesVisited, $arrayName . "[\"" . $field->getName() . "_\"]") ;
    }
  }

  protected function u_(Entity $entity, array $tablesVisited, $arrayName){
    $u_ = $entity->getFieldsU_NotReferenced($tablesVisited);

    foreach ($u_ as $field ) {
      array_push($tablesVisited, $entity->getName());
      $this->string .= $this->fields($field->getEntity()->getName(), $arrayName . "[\"" . $field->getAlias("_") . "_\"]");
    }

  }

  protected function fields($tableName, $arrayName){
    $this->string .= "    if(" . $arrayName . ".id !=  \"undefined\"){
      this.dd.storage.setItem(\"" . $tableName . "\" + " . $arrayName . ".id, " . $arrayName . ");
      delete " . $arrayName . ";
    }
";
  }

}
