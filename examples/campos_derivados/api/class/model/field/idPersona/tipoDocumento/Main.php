<?php

require_once("class/model/Field.php");

class FieldIdPersonaTipoDocumentoMain extends Field {

  protected $type = "varchar";
  protected $fieldType = "nf";
  protected $unique = false;
  protected $notNull = false;
  protected $default = "DNI";
  protected $length = "45";
  protected $main = false;
    
  public static function name() { return  "tipo_documento"; }
  public static function alias() { return  "td"; }


  public function getEntity(){ return new IdPersonaEntity; }


}
