<?php

require_once("class/model/Field.php");

class FieldCursoHorario extends Field {

  protected $type = "varchar";
  protected $fieldType = "nf";
  protected $unique = false;
  protected $notNull = false;
  protected $default = false;
  protected $length = "100";
  protected $main = false;

  public static function name() { return  "horario"; }
  public static function alias() { return  "hor"; }


  public function getEntity(){ return new CursoEntity; }

}
