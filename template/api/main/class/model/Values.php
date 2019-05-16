<?php

abstract class EntityValues { //manipulacion de valores de una entidad

  abstract public function setRow(array $row = NULL);

  public function __construct($row = NULL){
    $this->setRow($row);
  }

  public static function getInstaceFromString($entity, array $row = NULL) { //crear instancias de values
    //TODO: Implementar metodo setRow fuera del constructor
    $name = snake_case_to("XxYy", $entity);
    $class = new $name;
    $class->setRow($row);
    return $class;
  }

  protected function formatDate($value, $format = 'd/m/Y'){
    if(gettype($value) === "string") $value = SpanishDateTime::createFromFormat("Y-m-d", $value);
    return ($value) ? $value->format($format) : null;
  }

  protected function formatString($value, $format = null){
    switch($format){
      case "XxYy": return str_replace(" ", "", ucwords(str_replace("_", " ", strtolower($value))));
      case "xxyy": case "xy": return strtolower(str_replace("_", "", $value));
      case "Xx Yy": return ucwords(str_replace("_", " ", strtolower($value)));
      case "Xx yy": case "X y": return ucfirst(str_replace("_", " ", strtolower($value)));
      case "xxYy": return str_replace(" ", "", lcfirst(ucwords(str_replace("_", " ", strtolower($value)))));
      case "xx-yy": case "x-y": return strtolower(str_replace("_", "-", $value));
      case "XX YY": case "X Y": case "X": return strtoupper(str_replace("_", " ", $value));
      case "XY": case "XXYY": return strtoupper(str_replace("_", "", $value));

      default: return $value;
    }
  }

}
