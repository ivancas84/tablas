<?php

require_once("class/model/Field.php");

class FieldIdPersonaTelefonos extends Field {

  protected $type = "varchar";
  protected $fieldType = "nf";
  protected $unique = false;
  protected $notNull = false;
  protected $default = false;
  protected $length = "255";
  protected $main = false;

  public static function name() { return  "telefonos"; }
  public static function alias() { return  "tel"; }


  public function getEntity(){ return new IdPersonaEntity; }


}
