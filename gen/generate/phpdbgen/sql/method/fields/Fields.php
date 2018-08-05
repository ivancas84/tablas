<?php


class ClassSql_fields extends GenerateEntity {

  /**
  * Generar sql distinct fields
  * @param array $table Tabla de la estructura
  * @param string $string Codigo generado hasta el momento
  * @return string Codigo generado
  */
  protected function fields(){
    $pk = $this->getEntity()->getPk();
    $nfFk = $this->getEntity()->getFieldsByType(["nf","fk"]);

    $this->string .= "{\$t}." . $this->getEntity()->getPk()->getName() . " AS {\$p}" . $this->getEntity()->getPk()->getName() . ", ";

    foreach ( $nfFk as $field ) {
      $this->string .=  "{\$t}." . $field->getName() . " AS {\$p}" . $field->getName() . ", ";

    }

    $pos = strrpos($this->string, ",");
    $this->string = substr_replace($this->string , "" , $pos, 2);

  }



  protected function end(){
    $this->string .= "\";
  }

";
  }


  protected function start(){
    $this->string .= "
  public function fields(\$prefix = ''){
    \$t = (empty(\$prefix)) ?  '" . $this->getEntity()->getAlias() . "'  : \$prefix;
    \$p = (empty(\$prefix)) ?  ''  : \$prefix . '_';

    return \"";
  }


  public function generate(){
    $this->start();
    $this->fields();
    $this->end();
    return $this->string;
  }


}
