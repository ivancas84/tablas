<?php

require_once("class/model/entity/sede/Main.php");

class SedeEntity extends SedeEntityMain {
  public function getFieldsFk(){
    $array = parent::getFieldsFk();
    array_push($array, new FieldSedeCoordinador());
    return $array;
  }
}
