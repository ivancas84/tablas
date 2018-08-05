<?php


/**
 * Generar metodo para definir sql
 * @param array $table Tabla de la estructura
 * @param string &$string Codigo generado
 */
class ClassSql__fieldsLabel extends GenerateEntity{


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
  public function _fieldsLabel(\$prefix){
    \$t = (empty(\$prefix)) ?  '{$this->entity->getAlias()}'  : \$prefix;
    \$p = (empty(\$prefix)) ?  ''  : \$prefix . '_';

    return \"CONCAT_WS(', ', ";
  }

  public function body(){
    $nf = $this->entity->getFieldsByType(array("pk","nf"));

    foreach ( $nf as $field ) {
      if($field->isMain()){ $this->string .= "{\$t}.{$field->getName()}, "; }
    }
  }


  protected function end(){
    $this->string = rtrim($this->string); //borrar espacios de mas
    $this->string = rtrim($this->string, ", "); //borrar ultima coma
    $this->string .= ") AS {\$p}label\";
  }

";
  }

}

?>
