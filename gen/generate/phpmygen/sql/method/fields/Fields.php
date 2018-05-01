<?php


class ClassSql_fields extends GenerateEntity {

  /**
  * Generar sql distinct fields
  * @param array $table Tabla de la estructura
  * @param string $string Codigo generado hasta el momento
  * @return string Codigo generado
  */
  protected function fields(Entity $entity){
    $pk = $entity->getPk();
    $nfFk = $entity->getFieldsByType(["nf","fk"]);

    $this->string .= "{\$prefix}." . $entity->getPk()->getName() . " AS {\$prf}" . $entity->getPk()->getName() . ", ";

    foreach ( $nfFk as $field ) {
      $this->string .=  "{\$prefix}." . $field->getName() . " AS {\$prf}" . $field->getName() . ", ";

    }

    $this->string .= "
";
  }



  protected function end(){
    $this->string .= "\";
  }

";
  }


  protected function start(){
    $this->string .= "
  //***** @override *****
  public static function _fields(\$prefix = ''){
    if(empty(\$prefix)) {
      \$prefix = '" . $this->getEntity()->getAlias() . "';
      \$prf = '';
    } else {
      \$prf = \$prefix . '_';
    }

    return \"";
  }


  public function generate(){
    $this->start();
    $this->fields($this->getEntity());
    $this->end();
    return $this->string;
  }


}
