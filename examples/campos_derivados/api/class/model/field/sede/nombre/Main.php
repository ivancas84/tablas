<?php

require_once("class/model/Field.php");

class FieldSedeNombreMain extends Field {

  protected $type = "varchar";
  protected $fieldType = "nf";
  protected $unique = false;
  protected $notNull = true;
  protected $default = false;
  protected $length = "255";
  protected $main = false;
    
  public static function name() { return  "nombre"; }
  public static function alias() { return  "nom"; }


  public function getEntity(){ return new SedeEntity; }


}
