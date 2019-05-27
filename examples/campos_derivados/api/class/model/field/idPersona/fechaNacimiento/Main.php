<?php

require_once("class/model/Field.php");

class FieldIdPersonaFechaNacimientoMain extends Field {

  protected $type = "date";
  protected $fieldType = "nf";
  protected $unique = false;
  protected $notNull = false;
  protected $default = false;
  protected $length = false;
  protected $main = false;
    
  public static function name() { return  "fecha_nacimiento"; }
  public static function alias() { return  "fn"; }


  public function getEntity(){ return new IdPersonaEntity; }


}
