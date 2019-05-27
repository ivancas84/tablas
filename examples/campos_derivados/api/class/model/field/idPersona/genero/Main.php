<?php

require_once("class/model/Field.php");

class FieldIdPersonaGeneroMain extends Field {

  protected $type = "varchar";
  protected $fieldType = "nf";
  protected $unique = false;
  protected $notNull = true;
  protected $default = false;
  protected $length = "45";
  protected $main = false;
    
  public static function name() { return  "genero"; }
  public static function alias() { return  "gen"; }


  public function getEntity(){ return new IdPersonaEntity; }


}
