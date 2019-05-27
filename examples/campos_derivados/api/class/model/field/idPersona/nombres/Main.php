<?php

require_once("class/model/Field.php");

class FieldIdPersonaNombresMain extends Field {

  protected $type = "varchar";
  protected $fieldType = "nf";
  protected $unique = false;
  protected $notNull = true;
  protected $default = false;
  protected $length = "100";
  protected $main = false;
    
  public static function name() { return  "nombres"; }
  public static function alias() { return  "nom"; }


  public function getEntity(){ return new IdPersonaEntity; }


}
