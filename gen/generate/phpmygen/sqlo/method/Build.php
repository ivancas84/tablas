
<?php


class ClassSqlo_build extends GenerateEntity {


   public function generate(){
    if (!$this->entity->hasRelations()) return;
    $this->start();
    $this->buildMain();
    $this->recursive($this->getEntity());
    $this->end();
    return $this->string;
  }

  protected function start(){
    $this->string .= "
  //@override
  public function build(array \$row){
";
  }

  protected function buildMain(){
    $this->string .= "    if(empty(\$row)) return null;
    \$row_ = \$this->_build(\$row);
";
  }

  protected function recursive(Entity $entity, array $tablesVisited = NULL, $arrayName = "", $prefixField = "") {
    if(is_null($tablesVisited)) $tablesVisited = array();

    if (!empty($arrayName)){
      $this->build($entity, $arrayName, $prefixField);
    } else {
      $arrayName = "\$row_";
    }

    $this->fk($entity, $tablesVisited, $arrayName, $prefixField);
    $this->u_($entity, $tablesVisited, $arrayName, $prefixField);
  }


  protected function build(Entity $entity, $arrayName, $prefixField, $createArray = true){
    $this->string .= "    if(!empty(\$row[\"" . $prefixField . "id\"])){
      \$sqlo = new " . $entity->getName("XxYy"). "Sqlo;
      " . $arrayName . " = \$sqlo->_build(\$row, \"" . $prefixField. "\");
    }
";
  }



  protected function fk(Entity $entity, array $tablesVisited, $arrayName, $prefixField){
    $fk = $entity->getFieldsFkNotReferenced($tablesVisited);
    foreach ($fk as $field ) {
      array_push($tablesVisited, $entity->getName());
      $this->recursive($field->getEntityRef(), $tablesVisited, $arrayName . "[\"" . $field->getName() . "_\"]", $prefixField . $field->getAlias() . "_") ;
    }
  }

  protected function u_(Entity $entity, array $tablesVisited, $arrayName, $prefixField){
    $u_ = $entity->getFieldsU_NotReferenced($tablesVisited);

    foreach ($u_ as $field ) {
      $this->build($field->getEntity(), $arrayName . "[\"" . $field->getAlias("_") . "_\"]", $prefixField . $field->getAlias("_") . "_");
    }

  }


    protected function end(){
      $this->string .= "    return \$row_;
  }
  ";
    }



}
