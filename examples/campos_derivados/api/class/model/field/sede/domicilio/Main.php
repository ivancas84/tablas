<?php

require_once("class/model/Field.php");

class FieldSedeDomicilioMain extends Field {

  protected $type = "bigint";
  protected $fieldType = "_u";
  protected $unique = true;
  protected $notNull = false;
  protected $default = false;
  protected $length = "20";
  protected $main = false;
    
  public static function name() { return  "domicilio"; }
  public static function alias() { return  "dom"; }


  public function getEntity(){ return new SedeEntity; }
  
  public function getEntityRef(){ return new DomicilioEntity; }


}
