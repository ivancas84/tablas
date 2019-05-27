<?php

require_once("class/model/Field.php");

class FieldSedeCoordinador extends Field {

  protected $type = "bigint";
  protected $fieldType = "fk";
  protected $unique = false;
  protected $notNull = false;
  protected $default = false;
  protected $length = "20";
  protected $main = false;

  public static function name() { return  "coordinador"; }
  public static function alias() { return  "coo"; }


  public function getEntity(){ return new SedeEntity; }

  public function getEntityRef(){ return new IdPersonaEntity; }


}
