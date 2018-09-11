<?php

require_once("class/model/Field.php");

class FieldCursoAltaMain extends Field {

  protected $type = "timestamp";
  protected $fieldType = "nf";
  protected $unique = false;
  protected $notNull = true;
  protected $default = "CURRENT_TIMESTAMP";
  protected $length = false;
  protected $main = false;
    
  public static function name() { return  "alta"; }
  public static function alias() { return  "alt"; }


  public function getEntity(){ return new CursoEntity; }


}
