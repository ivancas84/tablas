<?php

require_once("class/model/Field.php");

class FieldIdPersonaSobrenombreMain extends Field {

  protected $type = "varchar";
  protected $fieldType = "nf";
  protected $unique = false;
  protected $notNull = false;
  protected $default = false;
  protected $length = "255";
  protected $main = false;
    
  public static function name() { return  "sobrenombre"; }
  public static function alias() { return  "sob"; }


  public function getEntity(){ return new IdPersonaEntity; }


}
