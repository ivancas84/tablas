<?php

require_once("class/model/Entity.php");
require_once("config/entityClasses.php");

class CursoEntityMain extends Entity {

  public static function name() { return "curso"; }
  public static function alias() { return "curs"; }

  public function getPk(){
    return new FieldCursoId;
  }

  public function getFieldsNf(){
    return array(
      new FieldCursoEstado,
      new FieldCursoAlta,
      new FieldCursoBaja,
    );
  }

  public function getFieldsMu(){
    return array(
      new FieldCursoComision,
      new FieldCursoCargaHoraria,
    );
  }


}
