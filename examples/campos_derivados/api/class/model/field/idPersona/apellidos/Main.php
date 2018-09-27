<?php

require_once("class/model/Field.php");

class FieldIdPersonaApellidosMain extends Field {

  protected $type = "varchar";
  protected $fieldType = "nf";
  protected $unique = false;
  protected $notNull = true;
  protected $default = false;
  protected $length = "100";
  protected $main = false;
    
  public static function name() { return  "apellidos"; }
  public static function alias() { return  "ape"; }


  public function getEntity(){ return new IdPersonaEntity; }


}
