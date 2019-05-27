<?php

require_once("class/model/Entity.php");
require_once("config/entityClasses.php");

class SedeEntityMain extends Entity {

  public static function name() { return "sede"; }
  public static function alias() { return "sede"; }

  public function getPk(){
    return new FieldSedeId;
  }

  public function getFieldsNf(){
    return array(
      new FieldSedeNumero,
      new FieldSedeNombre,
      new FieldSedeOrganizacion,
      new FieldSedeObservaciones,
      new FieldSedeAlta,
      new FieldSedeBaja,
      new FieldSedeUsuario,
      new FieldSedeEstado,
      new FieldSedeApertura,
      new FieldSedeComisiones,
    );
  }

  public function getFields_U(){
    return array(
      new FieldSedeDomicilio,
    );
  }


}
