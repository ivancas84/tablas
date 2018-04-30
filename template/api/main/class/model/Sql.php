<?php

require_once("function/snake_case_to.php");
require_once("function/concat.php");
require_once("function/settypebool.php");
require_once("class/db/Interface.php");


//Facilitar la definicion de sql
//Para definir el sql se deben utilizar algunos métodos que requieren una conexion abierta a la base de datos, como por ejemplo "escapar caracteres"
//Facilitar la generacion de consultas SQL a traves de una serie de metodos que son de uso comun para todas las consultas
//Esta clase ofrece soporte y traduccion para motores MySql (prioritario) y Postgresql. Si se dificulta la traduccion para motores no prioritarios entonces no se definen los metodos
abstract class EntitySql {

  protected $entity; //Entity. Configuracion de la tabla
  protected $db;
    //Para definir el sql es necesaria la existencia de una clase de acceso abierta, ya que ciertos metodos, como por ejemplo "escapar caracteres" lo requieren.
    //Ademas, ciertos metodos requieren determinar el motor de base de datos para definir la sintaxis SQL adecuada

  public function _mappingField($field){ throw new BadMethodCallException("Not Implemented"); }

  public function mappingField($field){
    $field_ = $this->_mappingField($field);
    if(!$field_) throw new Exception("Campo no reconocido");
    return $field_;
  }

  public function filterFields(Render $render){
    if(empty($render->getFields())) return false;

    $fields = [];
    foreach ($render->getFields() as $field){
      $field_ = $this->mappingField($field);
      array_push($fields, $field_ . " AS " . $field);
    }
    
    return implode(", ", $fields);
  }

  public function setDb(DbInterface $db){
    $this->db = $db;
  }

  //***** metodos abstractos *****
  public function conditionSearch($search = "") { throw new BadMethodCallException("Not Implemented"); } //Definir condicion de busqueda simple

  //Busqueda avanzada
  //@param   Array $advanced
  //  [
  //    0 => "field"
  //    1 => "=", "!=", ">=", "<=", "<", ">", ...
  //    2 => "value" array|string|int|boolean|date (si es null no se define busqueda, si es un array se definen tantas busquedas como elementos tenga el array)
  //    3 => "AND" | "OR" | null (opcional, por defecto AND)
  //  ]
  //  Array(
  //    Array("field" => "field", "value" => array|string|int|boolean|date (si es null no se define busqueda, si es un array se definen tantas busquedas como elementos tenga el array) [, "option" => "="|"=~"|"!="|"<"|"<="|">"|">="|true (no nulos)|false (nulos)][, "mode" => "and"|"or"]
  //    ...
  //  )
  //  )
  public function conditionAdvanced(array $advanced) {
    if(!count($advanced)) return "";
    $conditionMode = $this->conditionAdvancedRecursive($advanced);
    return $conditionMode["condition"];
  }

  public function conditionAdvancedRecursive(array $advanced){
    if(!is_array($advanced[0])) {
      $mode = (empty($advanced[3])) ? "AND" : $advanced[3];
      $condicion = $this->_conditionAdvanced($advanced[0], $advanced[1], $advanced[2], "");
      return ["condition" => $condicion, "mode" => $mode];

    } else {
      return $this->conditionAdvancedIterable($advanced) ;
    }
  }

  protected function conditionAdvancedIterable(array $advanced) {
    $conditionModes = array();

    for($i = 0; $i < count($advanced); $i++){
      $conditionMode = $this->conditionAdvancedRecursive($advanced[$i]);
      array_push($conditionModes, $conditionMode);
    }

    $modeReturn = $conditionModes[0]["mode"];
    $condition = "";

    foreach($conditionModes as $cm){
      $mode = $cm["mode"];
      if(!empty($condition)) $condition .= $mode . " ";
      $condition.= $cm["condition"];
    }

    return ["condition"=>"(".$condition.")", "mode"=>$modeReturn];
  }


  protected function _conditionAdvanced($field, $option, $value){ throw new BadMethodCallException("Not Implemented"); } //Definir sql con los campos de la tabla principal

  public function fields(){ throw new BadMethodCallException("Not Implemented"); } //Definir sql con los campos

  public function fieldId(){ return $this->entity->getAlias() . "." . $this->entity->getPk()->getName(); } //Se define el identificador en un metodo independiente para facilitar la reimplementacion para aquellos casos en que el id tenga un nombre diferente al requerido, para el framework es obligatorio que todas las entidades tengan una pk con nombre "id"

  public function from(){
    return " FROM " . $this->entity->getSna_() . "
";
  }

  public function limit(Render $render){
    $page = (empty($render->getPage())) ? 1 : $render->getPage();
    if ($render->getSize()) {
        return " LIMIT " . $render->getSize() . " OFFSET " . ( ($page - 1) * $render->getSize()) . "
";
    }
    return "";
  }

  //***** Definir condicion de busqueda de texto aproximada (en base al motor de base de datos define la sintaxis correspondiente *****
  protected function _conditionTextApprox($field, $value){
    return "(lower(" . $field . ") LIKE lower('%" . $value . "%'))";
  }

  //***** Definir condicion de busqueda de texto aproximada (en base al motor de base de datos define la sintaxis correspondiente *****
  protected function _conditionDateApprox($field, $value){
    if($this->db->getDbms() == "mysql") return "(CAST(DATE_FORMAT(" . $field . ", '%d/%m/%Y') AS CHAR) LIKE '%" . $value . "%' )";
    else return "(TO_CHAR(" . $field . ", 'DD/MM/YYYY') LIKE '%" . $value . "%' )";
  }

   //***** Definir condicion de busqueda de texto aproximada (en base al motor de base de datos define la sintaxis correspondiente *****
  protected function _conditionDate($field, $value, $option){
    if($this->db->getDbms() == "mysql") return "(" . $field . " " . $option. " '" . $value . "')";
    else return "(" . $field . " " . $option. " TO_DATE('" . $value . "', 'YYYY-MM-DD') )";
  }




  protected function _conditionNumberApprox($field, $value){
    if($this->db->getDbms() == "mysql") return "(CAST(" . $field . " AS CHAR) LIKE '%" . $value . "%' )";
    else return "(trim(both ' ' from to_char(" . $field . ", '99999999999999999999')) LIKE '%" . $value . "%' ) ";
  }


  protected function _conditionTimestampApprox($field, $value){
    if($this->db->getDbms() == "mysql") return "(CAST(DATE_FORMAT(" . $field . ", '%d/%m/%Y %H:%i') AS CHAR) LIKE '%" . $value . "%' )";
    else return "(TO_CHAR(" . $field . ", 'DD/MM/YYYY HH:MI') LIKE '%" . $value . "%' )";
  }

  protected function _conditionYearApprox($field, $value){
    if($this->db->getDbms() == "mysql") return "(CAST(DATE_FORMAT(" . $field . ", '%Y') AS CHAR) LIKE '%" . $value . "%' )";
    else return "(TO_CHAR(" . $field . ", 'YYYY') LIKE '%" . $value . "%' )";
  }


  //***** Definir condicion de busqueda de texto *****
  protected function conditionText($field, $value, $option = "="){
    if($value === false) return "(" . $field . " IS NULL) ";
    if($value === true) return "(" . $field . " IS NOT NULL) ";
    if($option == "=~") return $this->_conditionTextApprox($field, $value);
    return "(lower(" . $field . ") " . $option . " lower('" . $value . "')) ";
  }

  protected function conditionDate($field, $value, $option = "="){
    if($value === false) return "(" . $field . " IS NULL) ";
    if($value === true) return "(" . $field . " IS NOT NULL) ";
    return $this->_conditionDate($field, $value, $option);



  }

  protected function conditionNumber($field, $value, $option = "="){
    if($value === false) return "(" . $field . " IS NULL) ";
    if($value === true) return "(" . $field . " IS NOT NULL) ";
    if($option === true) return "(" . $field . " IS NULL) ";
    if($option === false) return "(" . $field . " IS NOT NULL) ";
    return "(" . $field . " " . $option . " " . $value . ") ";
  }

  //***** Definir condicion de busqueda de booleano *****
  protected function conditionBoolean($field, $value = NULL){
    $v = (settypebool($value)) ? "true" : "false";
    return "(" . $field . " = " . $v . ") ";
  }



  //***** Definir todas las condiciones *****
  public function conditionAll(Render $render, $connect="WHERE"){
    $condS = $this->conditionSearch($render->getSearch());
    $sqlCond = concat($condS, $connect);
    $condA = $this->conditionAdvanced($render->getAdvanced());
    $sqlCond .= concat($condA, " AND", $connect, $sqlCond);
    $condO = $this->conditionAux();
    $sqlCond .= concat($condO, " AND", $connect, $sqlCond);
    $condP = $render->getCondition();
    $sqlCond .= concat($condP, " AND", $connect, $sqlCond);
    return $sqlCond;
  }

  //Filtrar campos unicos y definir condicion
  public function conditionUniqueFields(array $fields){
    $uniqueFields = $this->entity->getFieldsUnique();

    $advancedSearch = array();
    foreach($fields as $key => $value){
      foreach($uniqueFields as $field){
        if($key == $field->getName()) {
          if(!empty($value)) array_push($advancedSearch, [$key, "=", $value, "or"]);
        }
      }
    }

    return $this->conditionAdvanced($advancedSearch);
  }




  //Definir sql de campos
  public function fieldsFull(){ return $this->fields(); } //sobrescribir si existen relaciones

  //Definir sql condicion obligatoria
  //Utilizada generalmente para restringir visualización, CUIDADO CON LA PERSISTENCIA!!! las restricciones de visualización son también aplicadas al persistir, pudiendo no tener el efecto deseado.
  //@return "(condition) " //por las dudas dejar espacio despues de condicion
  //@example "(alias.field = value) "
  //@example "(pago.deleted = false) "
  public function conditionAux(){ return "";  } //Sobrescribir si existe condicion obligatoria

  //Definir etiqueta (concatenar campos principales)
  //Puede requerir relaciones completas!!!
  public function fieldsLabel(){
    return $this->entity->getAlias() . "." . $this->entity->getPk()->getName() . " AS label,
";
  }

  //Definir sql de campos de cadena de relaciones
  public function fieldsLabelFull(){ return ""; }  //sobrescribir si existen relaciones y son consideradas como campos principales

  //Definir sql con campos auxiliares
  public function fieldsAux(){ return ""; } //sobrescribir si se desean campos auxiliares

  //Definir sql con cadena de relaciones fk y u_
  public function join(){ return ""; } //Sobrescribir si existen relaciones fk u_

  //Definir sql con relacion auxiliar
  //Utilizada generalmente para restringir visualización, CUIDADO CON LA PERSISTENCIA!!! Las restricciones de visualización son también aplicadas al persistir, pudiendo no tener el efecto deseado.
  public function joinAux(){ return ""; } //Sobrescribir si existe relacion auxiliar

  //Ordenamiento de cadena de relaciones
  public function orderBy(Render $render){
    $orderByFields = $render->getOrder();
    if(empty($orderByFields)) return "";

    $sql = '';

    foreach($orderByFields as $key => $value){
      $value = ((strtolower($value) == "asc") || ($value === true)) ? "asc" : "desc";
      $sql_ = $this->mappingField($key) . " " . $value;
      $sql .= concat($sql_, ', ', ' ORDER BY', $sql);
    }
    return $sql;
  }


  public function isUpdatable(array $row){
    try {
      $row_ = $this->initializeUpdateSql($row);
      $this->formatSql($row_);
      return true;
    } catch(Exception $exception){
      return $exception->getMessage();
    }
  }

  public function isInsertable(array $row){
    try {
      $row_ = $this->initializeInsertSql($row);
      $this->formatSql($row_);
      return true;
    } catch(Exception $exception){
      return $exception->getMessage();
    }
  }



}
