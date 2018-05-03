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

    $this->string .= "{\$t}." . $entity->getPk()->getName() . " AS {\$p}" . $entity->getPk()->getName() . ", ";

    foreach ( $nfFk as $field ) {
      $this->string .=  "{\$t}." . $field->getName() . " AS {\$p}" . $field->getName() . ", ";

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
  public function _fields(\$prefix = ''){
    \$t = (empty(\$prefix)) ?  '" . $this->getEntity()->getAlias() . "'  : \$prefix;
    \$p = (empty(\$prefix)) ?  ''  : \$prefix . '_';

    return \"";
  }


  public function generate(){
    $this->start();
    $this->fields($this->getEntity());
    $this->end();
    return $this->string;
  }


}
