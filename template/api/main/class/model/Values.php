<?php


//SQL Object
//Permite crear instancias que sirven para definir SQL
abstract class EntityValues {

  public function formatDate($value, $format = 'd/m/Y'){
    if(gettype($value) === "string") $value = SpanishDateTime::createFromFormat("Y-m-d", $value);
    return ($value) ? $value->format($format) : null;
  }
  
}
