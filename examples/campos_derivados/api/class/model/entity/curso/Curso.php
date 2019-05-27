<?php

require_once("class/model/entity/curso/Main.php");

class CursoEntity extends CursoEntityMain {

  public function getFieldsNf(){
    $array = parent::getFieldsMu();
    array_push($array, new FieldCursoHorario);
    return $array;
  }

  public function getFieldsMu(){
    $array = parent::getFieldsMu();
    array_push($array, new FieldCursoProfesorActivo);
    return $array;
  }
}
