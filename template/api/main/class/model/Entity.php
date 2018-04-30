<?php

/**
 * Configuracion de una tabla
 * Esta clase no deberia poseer seters publicos. Una vez definidos sus atributos, no deberian poder modificarse.
 * Entity debe poseer toda la configuracion necesaria, no importa el contexto en que se este trabajando. Si un determinado contexto posee cierta configuracion se define en la clase Entity, por ejemplo, el atributo "schema" es exclusivo de un contexto de acceso a traves de Sistemas de Administracion de Base de Datos.
 */
abstract class Entity {
  protected static $structure = NULL; //array. Estructura de tablas. Debido a que la estructura utiliza clases concretas, debe asignarse luego de finalizada la generacion de archivos y solo cuando se requiera su uso.

  //http://php.net/manual/en/language.oop5.late-static-bindings.php
  public static function name(){ return null; }
  public static function alias(){ return null; }
  public static function schema(){ return DATA_SCHEMA; }


  //Metodo auxiliares para facilitar la definicion de consultas sql (se definen como metodos estaticos para facilitar la sintaxis)
  public static function n_(){ return static::name(); } //nombre de la tabla. El nombre de la tabla puede no coincidir con el de la entidad

  public static function s_(){ return (!empty(static::schema())) ?  static::schema() . '.' : ""; } //schema.
  public static function sn_(){ return static::s_() . static::n_(); } //schema.nombre
  public static function sna_(){ return static::s_() . static::n_() . " AS " . static::alias(); } //schema.nombre AS alias
  public static function a_(){ return static::alias() . "."; }

  public function getS_() { return static::s_(); }
  public function getSn_() { return static::sn_(); }
  public function getSna_() { return static::sna_(); }
  public function getN_() { return static::n_(); }





  function getPk() { throw new BadMethodCallException("Not Implemented"); }

  //Debido a que la estructura utiliza clases concretas, debe asignarse luego de finalizada la generacion de archivos
  public static function setStructure(array $structure){ self::$structure = $structure; }
  public static function getStructure(){ return self::$structure; }

  public function getName($format = null) {
    switch($format){
      case "XxYy": return str_replace(" ", "", ucwords(str_replace("_", " ", strtolower(static::name()))));
      case "xxyy": return strtolower(str_replace("_", "", static::name()));
      case "Xx Yy": return ucwords(str_replace("_", " ", strtolower(static::name())));
      case "Xx yy": return ucfirst(str_replace("_", " ", strtolower(static::name())));
      case "xxYy": return str_replace(" ", "", lcfirst(ucwords(str_replace("_", " ", strtolower(static::name())))));
      case "xx-yy": return strtolower(str_replace("_", "-", static::name()));
      default: return static::name();
    }
  }


  public function getAlias($format = null) {
    switch($format){
     case ".": return (!empty(static::alias())) ?  static::alias() . '.' : "";
     case "Xx"; return ucfirst(strtolower(static::alias()));
     default: return static::alias();
    }

    return static::alias();
  }

  public function getSchema() { return static::schema(); }

  //***** fields pk nf fk *****
  public function getFields(){
    $merge =  array_merge($this->getFieldsNf(), $this->getFieldsFk());
    array_unshift($merge, $this->getPk());
    return $merge;
  }

  //***** fields nf *****
  public function getFieldsNf(){ return array(); }

  //***** fields fk (mu y _u)  *****
  public function getFieldsFk(){ return array_merge($this->getFieldsMu(), $this->getFields_U()); }

  //***** fields mu *****
  public function getFieldsMu(){ return array(); }

  //***** fields _u *****
  public function getFields_U(){ return array(); }

  //***** fields ref (um y u_) *****
  public function getFieldsRef(){ return array_merge($this->getFieldsUm(), $this->getFieldsU_()); }

  //***** fields um *****
  public function getFieldsUm(){
    if(self::getStructure() == NULL) throw new Exception("Debe setearse la estructura");
    $fields = array();
    foreach(self::getStructure() as $entity){
      foreach($entity->getFieldsMu() as $fieldConfig){
        if($fieldConfig->getEntityRef()->getName() == $this->getName()){
          array_push($fields, $fieldConfig);
        }
      }
    }
    return $fields;
  }

  //***** fields u_ *****
  public function getFieldsU_(){
    if(self::getStructure() == NULL) throw new Exception("Debe setearse la estructura");
    $fields = array();
    foreach(self::getStructure() as $entity){
      foreach($entity->getFields_U() as $fieldConfig){
        if($fieldConfig->getEntityRef()->getName() == $this->getName()){
          array_push($fields, $fieldConfig);
        }
      }
    }
    return $fields;
  }


  //***** fields fk no referenciadas: Fields fk cuyo nombre de tabla referenciada no se encuentre en el parametro)
  public function getFieldsFkNotReferenced(array $referencedNames){
    $fieldsAux = $this->getFieldsFk();
    $fields = array();

    foreach($fieldsAux as $fieldAux){
      if(!in_array($fieldAux->getEntityRef()->getName(), $referencedNames)){
        array_push($fields, $fieldAux);
      }
    }

    return $fields;
  }

  //***** fields u_ no referenciadas: Fields u_ cuyo nombre de tabla no se encuentre en el parametro)
  public function getFieldsU_NotReferenced(array $referencedNames){
    $fieldsAux = $this->getFieldsU_();
    $fields = array();

    foreach($fieldsAux as $fieldAux){
      if(!in_array($fieldAux->getEntity()->getName(), $referencedNames)){
        array_push($fields, $fieldAux);
      }
    }

    return $fields;
  }

  //***** fields por tipo *****
  public function getFieldsByType(array $types){
    $fields = array();

    foreach($types as $type){
      switch($type){
        case "pk": array_push($fields, $this->getPk()); break;
        case "nf": $fields = array_merge($fields, $this->getFieldsNf()); break;
        case "fk": $fields = array_merge($fields, $this->getFieldsFk()); break;
        case "ref": $fields = array_merge($fields, $this->getFieldsRef()); break;
        case "u_": $fields = array_merge($fields, $this->getFieldsU_()); break;
        case "um": $fields = array_merge($fields, $this->getFieldsUm()); break;
        case "_u": $fields = array_merge($fields, $this->getFieldsU_()); break;
        case "mu": $fields = array_merge($fields, $this->getFieldsUm()); break;
      }
    }

    return $fields;
  }

  //Devolver campos unicos
  public function getFieldsUnique(){
    $unique = array();
    foreach($this->getFields() as $field){
      if($field->isUnique()) array_push($unique, $field);
    }
    return $unique;
  }


  //***** ordenamiento por defecto *****
  //por defecto se definen los campos principales nf de la tabla principal
  //Si se incluyen campos de relaciones, asegurarse de incluir las relaciones
  public function getOrder(){
    $fields = $this->getFieldsNf();
    $orderBy = array();

    foreach($fields as $field){
      if($field->isMain()){
        $orderBy[$field->getName()] = "asc";
      }
    }

    return $orderBy;
  }

  //Tiene relaciones? Utilizado generalmente para verificar si es viable la generacion de cierto codigo que requiere relaciones
  public function hasRelations(){ return ($this->hasRelationsFk() || $this->hasRelationsU_()) ? true : false; }
  public function hasRelationsFk(){ return (count($this->getFieldsFk())) ? true : false; }
  public function hasRelationsU_(){ return (count($this->getFieldsU_())) ? true : false; }

}
