<?php

require_once("class/model/Field.php");

class FieldSedeNumeroMain extends Field {

  protected $type = "varchar";
  protected $fieldType = "nf";
  protected $unique = true;
  protected $notNull = false;
  protected $default = false;
  protected $length = "45";
  protected $main = false;
    
  public static function name() { return  "numero"; }
  public static function alias() { return  "num"; }


  public function getEntity(){ return new SedeEntity; }


}
