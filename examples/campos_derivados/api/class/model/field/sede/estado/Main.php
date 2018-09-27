<?php

require_once("class/model/Field.php");

class FieldSedeEstadoMain extends Field {

  protected $type = "varchar";
  protected $fieldType = "nf";
  protected $unique = false;
  protected $notNull = false;
  protected $default = false;
  protected $length = "45";
  protected $main = false;
    
  public static function name() { return  "estado"; }
  public static function alias() { return  "est"; }


  public function getEntity(){ return new SedeEntity; }


}
