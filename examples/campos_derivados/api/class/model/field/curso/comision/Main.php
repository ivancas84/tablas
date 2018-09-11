<?php

require_once("class/model/Field.php");

class FieldCursoComisionMain extends Field {

  protected $type = "bigint";
  protected $fieldType = "mu";
  protected $unique = false;
  protected $notNull = true;
  protected $default = false;
  protected $length = "20";
  protected $main = false;
    
  public static function name() { return  "comision"; }
  public static function alias() { return  "com"; }


  public function getEntity(){ return new CursoEntity; }
  
  public function getEntityRef(){ return new ComisionEntity; }


}
