<?php

require_once("class/model/Field.php");

class FieldIdPersonaNumeroDocumentoMain extends Field {

  protected $type = "varchar";
  protected $fieldType = "nf";
  protected $unique = true;
  protected $notNull = true;
  protected $default = false;
  protected $length = "45";
  protected $main = false;
    
  public static function name() { return  "numero_documento"; }
  public static function alias() { return  "nd"; }


  public function getEntity(){ return new IdPersonaEntity; }


}
