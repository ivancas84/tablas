<?php


class ClassSql_fields extends GenerateEntity {
  
  /**
  * Generar sql distinct fields
  * @param array $table Tabla de la estructura
  * @param string $string Codigo generado hasta el momento
  * @return string Codigo generado
  */
  protected function fields(Entity $entity, $tableName, $prefixField){
    $pk = $entity->getPk();
    $nfFk = $entity->getFieldsByType(["nf","fk"]);
    
    $this->string .= $tableName . "." . $entity->getPk()->getName() . " AS " . $entity->getPk()->getName() . ", ";
    
    foreach ( $nfFk as $field ) {
      $this->string .= $tableName . "." . $field->getName() . " AS " . $prefixField . $field->getName() . ", ";

    }
    
    $this->string .= "
";
  }


  
  protected function end(){
    $this->string .= "    \";
  }
";
  }
  

  protected function start(){
    $this->string .= "
  //***** @override *****
  public function fields(){
    return \"";
  }
  
    
  public function generate(){
    $this->start();
    $this->fields($this->getEntity(), $this->getEntity()->getAlias(), "");
    $this->end();  
    return $this->string;
  }


}
