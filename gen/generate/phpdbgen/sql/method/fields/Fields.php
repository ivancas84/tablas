<?php


class ClassSql_fields extends GenerateEntity {
  public function generate(){
    $this->start();
    $this->fields();
    $this->end();
    return $this->string;
  }








  protected function start(){
    $this->string .= "
  public function fields(\$prefix = ''){
    \$p = (empty(\$prefix)) ?  ''  : \$prefix . '_';
    return '
";
  }


  /**
  * Generar sql distinct fields
  * @param array $table Tabla de la estructura
  * @param string $string Codigo generado hasta el momento
  * @return string Codigo generado
  */
  protected function fields(){
    $pk = $this->getEntity()->getPk();
    $nfFk = $this->getEntity()->getFieldsByType(["nf","fk"]);

    $this->string .= "' . \$this->_mappingField(\$p.'{$this->getEntity()->getPk()->getName()}', \$prefix) . ' AS ' . \$p.'{$this->getEntity()->getPk()->getName()},
";
    foreach ( $nfFk as $field ) {
      $this->string .= "' . \$this->_mappingField(\$p.'{$field->getName()}', \$prefix) . ' AS ' . \$p.'{$field->getName()},
";

    }

    $pos = strrpos($this->string, ",");
    $this->string = substr_replace($this->string , "" , $pos, 2);

  }


  protected function end(){
    $this->string .= "';
  }

  ";
  }




}
