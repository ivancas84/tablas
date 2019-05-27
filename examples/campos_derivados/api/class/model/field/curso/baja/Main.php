<?php

require_once("class/model/Field.php");

class FieldCursoBajaMain extends Field {

  protected $type = "timestamp";
  protected $fieldType = "nf";
  protected $unique = false;
  protected $notNull = false;
  protected $default = false;
  protected $length = false;
  protected $main = false;
    
  public static function name() { return  "baja"; }
  public static function alias() { return  "baj"; }


  public function getEntity(){ return new CursoEntity; }


}
