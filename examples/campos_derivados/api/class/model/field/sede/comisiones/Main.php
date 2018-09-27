<?php

require_once("class/model/Field.php");

class FieldSedeComisionesMain extends Field {

  protected $type = "int";
  protected $fieldType = "nf";
  protected $unique = false;
  protected $notNull = true;
  protected $default = false;
  protected $length = "10";
  protected $main = false;
    
  public static function name() { return  "comisiones"; }
  public static function alias() { return  "com"; }


  public function getEntity(){ return new SedeEntity; }


}
