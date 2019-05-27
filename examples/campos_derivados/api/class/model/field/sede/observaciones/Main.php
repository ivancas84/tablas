<?php

require_once("class/model/Field.php");

class FieldSedeObservacionesMain extends Field {

  protected $type = "text";
  protected $fieldType = "nf";
  protected $unique = false;
  protected $notNull = false;
  protected $default = false;
  protected $length = "65535";
  protected $main = false;
    
  public static function name() { return  "observaciones"; }
  public static function alias() { return  "obs"; }


  public function getEntity(){ return new SedeEntity; }


}
