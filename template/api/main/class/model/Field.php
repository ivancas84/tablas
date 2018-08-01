<?php

require_once("function/snake_case_to.php");


abstract class Field {

  protected $default; //valor por defecto definido en base de datos (puede ser null)
    //false: El dato no tiene definido valor por defecto
  protected $length; //longitud del field
    //false: El dato no tiene definida longitud
  protected $notNull; //flag para indicar si es un campo no nulo
  protected $type; //string. tipo de datos definido en la base de datos
  protected $dataType; //tipo de datos generico
  protected $fieldType; //string. tipo de field
    //"pk": Clave primaria
    //"nf": Field normal
    //"mu": Clave foranea muchos a uno
    //"_u": Clave foranea uno a uno
  protected $unique; //flag para indicar si es un campo unico

  protected $subtype = null; //tipo de datos avanzado
    //text texto simple
    //textarea texto grande
    //checkbox Booleanos
    //date
    //timestamp
    //select_int Conjunto de enteros definido, los valores se definen en el atributo "selectValues"
    //select_text Conjunto de strings definido, los valores se definen en el atributo "selectValues"
    //cuil Texto para cuil
    //dni Texto para dni

  protected $typescriptType = null; //tipo de datos typescript

  protected $main = null; //flag para indicar si es un campo principal.
    //Por defecto se define la clave primaria como campo principal. En versiones anteriores se hacia la siguiente logica:
    // Si tiene algun campo main, se define el main
    // Si no tiene campo main, se define el unique
    // Si no tiene campo unique, se define la pk.
    // Pero debido a la complicacion en la logica y a la confusion que generaba se decidio dejar por defecto a la pk como campo principal siempre y definir adicionalmente a la pk los campos unique. El desarrollador debera cambiar este comportamiento manualmente.

  protected $selectValues = array();
    //si subtype = "select_text", deben asignarse valores "text"
    //si subtype = "select_int", deben asignarse valores "int"

  protected $admin = true; //administracion de campo, al desactivarlo, no se incluye el campo en los formularios de administracion

  public function __construct() {
    $this->defineDataType($this->type);
    $this->defineSubtype($this->dataType, $this->fieldType);
    $this->defineNotNull($this->subtype);
    $this->redefineLength($this->length, $this->type, $this->subtype);
  }

  public static function name(){ return null; }
  public static function alias(){ return null; }

  //Retornar instancia de Entity correspondiente al field
  abstract function getEntity();

  //Retornar instancia de Entity referenciado por el field
  //Metodo semiabstracto, debe sobrescribirse para aquellos fields que sean fk
  public function getEntityRef(){ return null; }

  public function getDefault(){ return $this->default; }
  public function getFieldType(){ return $this->fieldType; }
  public function getLength(){ return $this->length; }
  public function getSubtype(){ return $this->subtype; }
  public function getDataType(){ return $this->dataType; }


  public function getAlias($format = null) {
    switch($format){
      case "Xx": return ucfirst(strtolower(static::alias()));
      case ".": return (!empty(static::alias())) ? static::alias() . '.' : '';
      case "_Xx": return $this->getEntity()->getAlias("Xx") . $this->getAlias("Xx");
      case "_": return $this->getEntity()->getAlias() . $this->getAlias();

      default: return static::alias();
    }

  }

  public function getName($format = null) {
    switch($format){
      case "XxYy": return str_replace(" ", "", ucwords(str_replace("_", " ", strtolower(static::name()))));
      case "xxyy": return strtolower(str_replace("_", "", static::name()));
      case "Xx Yy": return ucwords(str_replace("_", " ", strtolower(static::name())));
      case "xxYy": return str_replace(" ", "", lcfirst(ucwords(str_replace("_", " ", strtolower(static::name())))));
      case "Xx yy": return ucfirst(str_replace("_", " ", strtolower(static::name())));
      case "_XxYy": return $this->getEntity()->getName("XxYy") . $this->getName("XxYy");
      case "_.":  return $this->getEntity()->getName() . "." . $this->getName();

      default: return static::name();
    }
  }



  /**
   * Retornar el nombre del field con formato de directorio
   * @return string Nombre del field con formato de directorio
   */
  public function getSelectValues(){ return $this->selectValues; }
  public function getType() { return $this->type; }
  public function isMain(){ return $this->main; }
  public function isNotNull(){ return $this->notNull; }
  public function isUnique(){ return $this->unique; }



  protected function defineNotNull($subtype){
    if ( is_null($this->notNull) ) {
      $this->notNull = ( ( $subtype == "checkbox" ) ) ? true : false;
    }
  }

  protected function defineDataType($type){
    if (is_null($this->dataType)) {
      switch ( $type ) {
        case "smallint":
        case "mediumint":
        case "int":
        case "integer":
        case "serial":
        case "bigint": $this->dataType = "integer"; break;
        case "tinyblob":
        case "blob":
        case "mediumblob":
        case "longblog": $this->dataType = "blob"; break;
        case "varchar":
        case "char":
        case "string":
        case "tinytext": $this->dataType = "string"; break;
        case "boolean":
        case "bool":
        case "tinyint": $this->dataType = "boolean"; break;
        case "float":
        case "real":
        case "decimal": $this->dataType = "float"; break;
        case "text": $this->dataType = "text"; break;
        case "datetime":
        case "timestamp": $this->dataType = "timestamp"; break;
        default: $this->dataType = $this->type;
      }
    }
  }

  protected function defineSubtype($dataType, $fieldType){
    if(is_null($this->subtype)){
      switch($fieldType){
        case "pk":
        case "nf":
          switch($dataType){
            case "string": $this->subtype = "text"; break;
            case "integer": $this->subtype = "integer"; break;
            case "float": $this->subtype = "float"; break;
            case "date": $this->subtype = "date"; break;
            case "timestamp": $this->subtype = "timestamp"; break;
            case "text": $this->subtype = "textarea"; break;
            case "blob": $this->subtype = "file_db"; break;
            case "boolean": $this->subtype = "checkbox"; break;
            case "time": $this->subtype = "time"; break;
            case "year": $this->subtype = "year"; break;
            default: $this->subtype = false; break;
          }
        break;

        case "fk": case "mu": case "_u":
          $this->subtype = "typeahead";
        break;
      }
    }
  }


  protected function defineUpdateNull(){
    if (is_null($this->updateNull) ) $this->updateNull = true;
  }


  protected function redefineLength($length, $type, $subtype){
    if ($this->length === false || $this->length === null){
      switch ($type) {
        case "tinyint": $this->length = 3; break;
        case "smallint": $this->length = 5; break;
        case "mediumint": $this->length = 8; break;
        case "int": $this->length = 10; break;
        case "integer": $this->length = 10; break;
        case "serial": $this->length = 10; break;
        case "bigint": $this->length = 20; break;
        case "tinyblob": $this->length = 255; break; //bytes
        case "blob": $this->length = 65535; break; //bytes (64KB)
        case "mediumblob": $this->length = 16777215; break; //bytes (16MB)
        case "longblog": $this->length = 4294967295; break; //bytes (4GB)
      }
    }

    if ($this->length === false || $this->length === null) {
      switch($subtype){
        case "text": $this->length = 45; break;
        case "cuil": $this->length = 11; break;
        case "dni": $this->length = 8; break;
      }
    }
  }
}
