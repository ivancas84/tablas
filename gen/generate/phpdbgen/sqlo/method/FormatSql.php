<?php


class Sqlo_FormatSql extends GenerateEntity{

protected function start(){
    $this->string .= "
  //Formato SQL
  public function formatSql(array \$row){
    \$row_ = array();
";

  }


  public function generate(){
    $this->start();
    $this->pk();
    $this->nf($this->getEntity());
    $this->fk($this->getEntity());
    $this->end();

    return $this->string;
  }

  protected function pk(){
    //verificar existencia de pk: Si la pk no esta definida entonces no se realizara la actualizacion
    $field = $this->getEntity()->getPk();
    switch ( $field->getDataType()) {
      case "integer":
        $this->string .= "    \$row_['" . $field->getName() . "'] = \$this->sql->positiveIntegerWithoutZerofill(\$row['" . $field->getName() . "']);
";
      break;
      case "string":
        $this->string .= "    \$row_['" . $field->getName() . "'] = \$this->sql->escapeString(\$row['" . $field->getName() . "']);
";
      break;


    }

  }



  protected function nf(Entity $entity){
    $nf = $entity->getFieldsNf();

    //redefinir valores de timestamp y date. Los valores timestamp y date se dividen en diferentes partes correspondientes a dia mes anio hora minutos y segundos. Dichas partes deben unirse en una sola variable
    foreach ( $nf as $field ) {
      switch ( $field->getDataType()) {
        case "timestamp": $this->timestamp($field); break;
        case "time":  $this->time($field); break;
        case "date": $this->date($field); break;
        case "year": $this->year($field); break;
        case "string": case "text": case "password": $this->string($field); break;
        case "integer": case "float": $this->number($field); break;
        case "boolean": $this->boolean($field); break;
      }
    }
    unset ( $field );
  }





  protected function integerNonZero(Field $field){

    $this->string .= "    if(isset(\$row['" . $field->getName() . "']) ) \$row_['" . $field->getName() . "'] = \$this->sql->positiveIntegerWithoutZerofill(\$row['" . $field->getName() . "']);
";

  }


  protected function number(Field $field){

    $this->string .= "    if(isset(\$row['" . $field->getName() . "'])) \$row_['" . $field->getName() . "'] = \$this->sql->numeric(\$row['" . $field->getName() . "']);
";

  }



  protected function timestamp(Field $field){

    $this->string .= "    if(isset(\$row['" . $field->getName() . "'])) \$row_['" . $field->getName() . "'] = \$this->sql->timestamp(\$row['" . $field->getName() . "']);
";
  }



  protected function time(Field $field){
    $this->string .= "    if(isset(\$row['" . $field->getName() . "'])) \$row_['" . $field->getName() . "'] = \$this->sql->time(\$row['" . $field->getName() . "']);
";

  }


  protected function date(Field $field){

    $this->string .= "    if(isset(\$row['" . $field->getName() . "'])) \$row_['" . $field->getName() . "'] = \$this->sql->date(\$row['" . $field->getName() . "']);
";

  }



  protected function year(Field $field){
    $this->string .= "    if(isset(\$row['" . $field->getName() . "'])) \$row_['" . $field->getName() . "'] = \$this->sql->year(\$row['" . $field->getName() . "']);
";

  }






  protected function string(Field $field){

      $this->string .= "    if(isset(\$row['" . $field->getName() . "'])) \$row_['" . $field->getName() . "'] = \$this->sql->escapeString(\$row['" . $field->getName() . "']);
";


  }



  protected function boolean(Field $field){

    $this->string .= "    if(isset(\$row['" . $field->getName() . "'])) \$row_['" . $field->getName() . "'] = \$this->sql->boolean(\$row['" . $field->getName() . "']);
";
  }



  protected function fk(Entity $entity){
    $fk = $entity->getFieldsFk();

    foreach ( $fk as $field) {
      switch ( $field->getDataType()) {
        case "integer": $this->integerNonZero($field); break;
        case "string": $this->string($field); break;
      }






    }
  }


  protected function end(){
    $this->string .= "
    return \$row_;
  }
";
  }




}
