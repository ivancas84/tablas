<?php

require_once("class/model/Field.php");

class FieldSedeUsuarioMain extends Field {

  protected $type = "bigint";
  protected $fieldType = "nf";
  protected $unique = false;
  protected $notNull = true;
  protected $default = "1";
  protected $length = "20";
  protected $main = false;
    
  public static function name() { return  "usuario"; }
  public static function alias() { return  "usu"; }


  public function getEntity(){ return new SedeEntity; }


}
