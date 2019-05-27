<?php


class Sql_initializeUpdate extends GenerateEntity{

protected function start(){
    $this->string .= "
  //@override
  public function initializeUpdate(array \$data){
";

  }


  public function generate(){
    $this->start();
    $this->nf($this->getEntity());
    $this->fk($this->getEntity());
    $this->end();

    return $this->string;
  }




  protected function nf(Entity $entity){
    //redefinir valores de timestamp y date. Los valores timestamp y date se dividen en diferentes partes correspondientes a dia mes anio hora minutos y segundos. Dichas partes deben unirse en una sola variable
    foreach ( $entity->getFieldsNf() as $field ) {
      if(!$field->isAdmin()) continue;
      switch ( $field->getDataType()) {
        case "timestamp": $this->fecha($field, "date(\"Y-m-d H:i:s\")"); break;
        case "time":  $this->fecha($field, "date(\"H:i:s\")"); break;
        case "date": $this->fecha($field, "date(\"Y-m-d\")"); break;
        case "year": $this->fecha($field, "date(\"Y\")"); break;
        case "string": case "text": $this->string($field); break;
        case "integer": case "float": $this->number($field); break;
        case "boolean": $this->boolean($field); break;
      }
    }
  }

  protected function fk(Entity $entity){
    foreach ( $entity->getFieldsFk() as $field) {
      if(!$field->isAdmin()) continue;
      $this->number($field);
    }
  }





  protected function number(Field $field){
    $default = ($field->getDefault()) ? $field->getDefault() : "\"null\"";

    $this->string .= "    if(array_key_exists('" . $field->getName() . "', \$data)) { if(!isset(\$data['" . $field->getName() . "']) || (\$data['" . $field->getName() . "'] == '')) ";
    if($field->isNotNull() && !$field->getDefault()){
      $this->string .= "throw new Exception('dato obligatorio sin valor: " . $field->getName() . "')";
    } else {
      $this->string .= "\$data['" . $field->getName() . "'] = " . $default;
    }
    $this->string .= "; }
";
  }


  protected function fecha(Field $field, $current_default){
    switch($field->getDefault()){
      case null: case false: $default =  "\"null\"";   break;
      case "CURRENT_TIMESTAMP": case "CURRENT_DATE": case "CURRENT_TIME": case "CURRENT_YEAR": $default = $current_default; break;
      default: $default =  "\"" .  $field->getDefault() . "\"";
    }

    $this->string .= "    if(array_key_exists('" . $field->getName() . "', \$data)) { if(empty(\$data['" . $field->getName() . "'])) ";

    if($field->isNotNull() && !$field->getDefault()){
      $this->string .= " throw new Exception('fecha/hora obligatoria sin valor: " . $field->getName() . "')";
    } else {
      $this->string .= " \$data['" . $field->getName() . "'] = " . $default . "";
    }
    $this->string .= "; }
";
  }


  protected function string(Field $field){
    $default = ($field->getDefault()) ? "\"" . $field->getDefault() . "\"": "\"null\"";

    $this->string .= "    if(array_key_exists('" . $field->getName() . "', \$data)) { if(empty(\$data['" . $field->getName() . "'])) ";

    if($field->isNotNull() && !$field->getDefault()){
      $this->string .= "throw new Exception('dato obligatorio sin valor: " . $field->getName() . "')";
    } else {
      $this->string .= "\$data['" . $field->getName() . "'] = " . $default;
    }
      $this->string .= "; }
";
  }



  protected function boolean(Field $field){

    switch($field->getDefault()){
      case "1":
      case "true":
      case "t":
        $default = "true";

      default:
        $default = "false";
    }

    $this->string .= "    if(array_key_exists('" . $field->getName() . "', \$data)) { if(!isset(\$data['" . $field->getName() . "']) || (\$data['" . $field->getName() . "'] == '')) \$data['" . $field->getName() . "'] = \"" . $default . "\"; }
";
  }





  protected function end(){
    $this->string .= "
    return \$data;
  }
";
  }

}
