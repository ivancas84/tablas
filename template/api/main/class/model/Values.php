<?php


//SQL Object
//Permite crear instancias que sirven para definir SQL
abstract class EntityValues {

  abstract public function setRow(array $row = NULL);

  public function __construct($row = NULL){
    $this->setRow($row);
  }

  public function formatDate($value, $format = 'd/m/Y'){
    if(gettype($value) === "string") $value = SpanishDateTime::createFromFormat("Y-m-d", $value);
    return ($value) ? $value->format($format) : null;
  }

  public static function getInstaceFromString($entity, array $row = NULL) { //crear instancias de values
    //TODO: Implementar metodo setRow fuera del constructor
    $name = snake_case_to("XxYy", $entity);
    $class = new $name;
    $class->setRow($row);
    return $class;
  }

}
