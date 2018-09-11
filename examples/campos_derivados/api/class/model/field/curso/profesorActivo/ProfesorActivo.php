<?php

require_once("class/model/Field.php");

class FieldCursoProfesorActivo extends Field {

  protected $type = "bigint";
  protected $fieldType = "mu";
  protected $unique = false;
  protected $notNull = true;
  protected $default = false;
  protected $length = "20";
  protected $main = false;

  public static function name() { return  "profesor_activo"; }
  public static function alias() { return  "pa"; }


  public function getEntity(){ return new CursoEntity; }

  public function getEntityRef(){ return new IdPersonaEntity; }


}
