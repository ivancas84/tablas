<?php

require_once("class/model/Field.php");

class FieldIdPersonaIdMain extends Field {

  protected $type = "bigint";
  protected $fieldType = "pk";
  protected $unique = true;
  protected $notNull = true;
  protected $default = false;
  protected $length = "20";
  protected $main = true;
    
  public static function name() { return  "id"; }
  public static function alias() { return  "id"; }


  public function getEntity(){ return new IdPersonaEntity; }


}
