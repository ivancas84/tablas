<?php

require_once("class/model/Field.php");

class FieldSedeDestinoMain extends Field {

  protected $type = "varchar";
  protected $fieldType = "nf";
  protected $unique = false;
  protected $notNull = false;
  protected $default = false;
  protected $length = "255";
  protected $main = false;
    
  public static function name() { return  "destino"; }
  public static function alias() { return  "des"; }


  public function getEntity(){ return new SedeEntity; }


}
