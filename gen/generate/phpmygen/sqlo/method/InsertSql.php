<?php

require_once("class/model/Entity.php");
require_once("generate/GenerateEntity.php");

class GenerateClassDataSqlMethodInsertSql extends GenerateEntity {
  
  public function __construct(Entity $entity) {
    parent::__construct($entity);    
  }
  
    //***** generar nombres de fields *****
  protected function fieldNames(){ 
    $fields = $this->getEntity()->getFields();

    foreach ( $fields as $field) {
      $this->string .= "    \$sql .= \"" . $field->getName() . ", \" ;
" ;       
    }
    unset($field, $fields);
  }
  
  //***** generar valores de fields *****
  protected function fieldValues(){
    $fields = $this->getEntity()->getFields();

    foreach($fields as $field){
      $this->string .= "    \$sql .= \$row['" . $field->getName() . "'] . \", \" ;
" ; 
    }
    unset($field, $fields);
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


  protected function start(){
    $this->string .= "
  //@override
  protected function _insertSql(array \$row){
    \$sql = \"
INSERT INTO \" . \$this->entity->getSn_() . \" (\";
";
  }
  

    
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
  


}
