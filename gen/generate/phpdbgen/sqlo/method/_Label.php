<?php


/**
 * Generar metodo para definir sql
 * @param array $table Tabla de la estructura
 * @param string &$string Codigo generado
 */
class ClassSqlo__label extends GenerateEntity{

  protected function isFeasible(){
    $nf = $this->entity->getFieldsNf();
    foreach ($nf as $field){
      if($field->isMain()) return true;
    }
    return false;
  }

  public function generate(){
    if (!$this->isFeasible()) return "";
    $this->start();
    $this->body();
    $this->end();

    return $this->string;
  }

  protected function start(){
    $this->string .= "
  //***** @override *****
  public function _label(\$row, \$prefix = ''){
    \$p = (empty(\$prefix)) ?  ''  : \$prefix . '_';

    \$fields = [];
";
  }

  public function body(){
    $nf = $this->entity->getFieldsByType(array("pk","nf"));

    foreach ( $nf as $field ) {
      if($field->isMain()) $this->string .= "    array_push(\$fields, \$row[\"{\$p}{$field->getName()}\"]);
";
    }
  }

  protected function end(){
    $this->string .= "    return implode(' ', \$fields);
  }
";
  }


}
