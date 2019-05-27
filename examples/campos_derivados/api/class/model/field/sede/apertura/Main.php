<?php

require_once("class/model/Field.php");

class FieldSedeAperturaMain extends Field {

  protected $type = "tinyint";
  protected $fieldType = "nf";
  protected $unique = false;
  protected $notNull = true;
  protected $default = false;
  protected $length = "1";
  protected $main = false;
    
  public static function name() { return  "apertura"; }
  public static function alias() { return  "ape"; }


  public function getEntity(){ return new SedeEntity; }


}
