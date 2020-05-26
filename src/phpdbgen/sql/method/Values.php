
<?php

class ClassSql_values extends GenerateEntity {

   public function generate(){
    if (!$this->entity->hasRelations()) return;
    $this->start();
    $this->recursive($this->getEntity());
    $this->end();
    return $this->string;
  }

  protected function start(){
    $this->string .= "  public function values(array \$json = NULL){
    \$row_['{$this->entity->getAlias()}'] = new {$this->entity->getName('XxYy')}(\$json);
";
  }

  protected function recursive(Entity $entity, array $tablesVisited = NULL, $arrayName = "", $prefixField = "") {
    if(is_null($tablesVisited)) $tablesVisited = array();

    if (!empty($arrayName)){
      $this->body($entity, $arrayName, $prefixField);
    } else {
      $arrayName = "\$json";
    }

    $this->fk($entity, $tablesVisited, $arrayName, $prefixField);
    //$this->u_($entity, $tablesVisited, $arrayName, $prefixField);
  }


  protected function body(Entity $entity, $arrayName, $prefixField, $createArray = true){
    $this->string .= "    \$row['{$prefixField}'] = new {$entity->getName('XxYy')}({$arrayName});
";
  }



  protected function fk(Entity $entity, array $tablesVisited, $arrayName, $prefixField){
    $fk = $entity->getFieldsFkNotReferenced($tablesVisited);
    foreach ($fk as $field ) {
      array_push($tablesVisited, $entity->getName());
      $p = (empty($prefixField)) ? $field->getAlias() : $prefixField . "_" . $field->getAlias();
      $this->recursive($field->getEntityRef(), $tablesVisited, $arrayName . "[\"" . $field->getName() . "_\"]", $p) ;
    }
  }

  protected function u_(Entity $entity, array $tablesVisited, $arrayName, $prefixField){
    $u_ = $entity->getFieldsU_NotReferenced($tablesVisited);

    foreach ($u_ as $field ) {
      $this->body($field->getEntity(), $arrayName . "[\"" . $field->getAlias("_") . "_\"]", $prefixField . $field->getAlias("_"));
    }

  }


    protected function end(){
      $this->string .= "    return \$row_;
  }

";
    }



}
